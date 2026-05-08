<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\StripeCredential;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'full_name'   => 'required|string',
            'email'       => 'required|email',
            'phone'       => 'required|string',
            'address'     => 'required|string',
            'city'        => 'required|string',
            'postal_code' => 'required|string',
        ]);

        $settings = StripeCredential::first();

        if (!$settings || !$settings->stripe_secret) {
            return back()->with('error', 'Stripe configuration missing!');
        }

        Stripe::setApiKey($settings->stripe_secret);
        $products = Product::whereIn('id', $request->product_ids)->get()->keyBy('id');

        $lineItems = [];
        $total = 0;
        $quantities = [];

        foreach ($request->product_ids as $id) {
            $product = $products->get($id);
            if (!$product) continue;

            $qty = intval($request->input("quantity.$id", 1));
            if ($qty < 1) $qty = 1;

            $stockMessage = $this->validateStock([$product], [$product->id => $qty]);

            if ($stockMessage) {
                return back()->withInput()->with('warning', $stockMessage);
            }

            $quantities[$id] = $qty;

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price * 100,
                ],
                'quantity' => $qty,
            ];

            $total += $product->price * $qty;
        }
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',

            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel'),

            'metadata' => [
                'user_id'     => Auth::id(),
                'full_name'   => $request->full_name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'address'     => $request->address,
                'city'        => $request->city,
                'postal_code' => $request->postal_code,
                'product_ids' => json_encode($request->product_ids),
                'quantities'  => json_encode($quantities),
            ],
        ]);

        return redirect($session->url)->with('success', 'Redirecting to Stripe checkout...');
    }

    public function success(Request $request)
    {
        $settings = StripeCredential::first();
        if (!$settings || !$settings->stripe_secret) {
            return redirect()->route('home')->with('error', 'Stripe config missing!');
        }

        Stripe::setApiKey($settings->stripe_secret);

        $session = Session::retrieve($request->session_id);

        if ($session->payment_status === 'paid') {
            $metadata = $session->metadata;

            $productIds = json_decode($metadata->product_ids ?? '[]', true);
            if (!is_array($productIds)) {
                $productIds = [$productIds];
            }
            $productIds = array_filter($productIds);

            $quantities = json_decode($metadata->quantities ?? '[]', true);
            if (!is_array($quantities)) {
                $quantities = [];
            }

            $userId = Auth::id() ?? $metadata->user_id;

            try {
                DB::transaction(function () use ($productIds, $quantities, $metadata, $userId) {
                    $products = Product::whereIn('id', $productIds)
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    $stockMessage = $this->validateStock($products, $quantities);

                    if ($stockMessage) {
                        throw new \RuntimeException($stockMessage);
                    }

                    $total = 0;
                    foreach ($productIds as $id) {
                        $product = $products->get($id);
                        $qty = max(intval($quantities[$id] ?? 1), 1);
                        $total += $product->price * $qty;
                    }

                    $order = Order::create([
                        'user_id'        => $userId,
                        'total'          => $total,
                        'address'        => $metadata->address,
                        'status'         => 'complete',
                        'full_name'      => $metadata->full_name,
                        'email'          => $metadata->email,
                        'phone'          => $metadata->phone,
                        'city'           => $metadata->city,
                        'postal_code'    => $metadata->postal_code,
                        'payment_method' => 'stripe',
                    ]);

                    foreach ($productIds as $id) {
                        $product = $products->get($id);
                        $qty = max(intval($quantities[$id] ?? 1), 1);

                        OrderItem::create([
                            'order_id'   => $order->id,
                            'product_id' => $product->id,
                            'quantity'   => $qty,
                            'price'      => $product->price * $qty,
                            'address'    => $metadata->address,
                        ]);

                        $product->decrement('quantity', $qty);
                    }
                });
            } catch (\RuntimeException $exception) {
                return redirect()->route('home')->with('warning', $exception->getMessage());
            }

            if ($userId) {
                Cart::where('user_id', $userId)
                    ->whereIn('product_id', $productIds)
                    ->delete();
            }

            return redirect()->route('home')->with('success', 'Payment successful!');
        }

        return redirect()->route('home')->with('error', 'Payment failed!');
    }

    public function cancel()
    {
        return redirect()->route('home')->with('error', 'Payment cancelled!');
    }

    private function validateStock(iterable $products, array $quantities): ?string
    {
        foreach ($products as $product) {
            if (! $product instanceof Product) {
                continue;
            }

            $quantity = max((int) ($quantities[$product->id] ?? 1), 1);

            if ((int) $product->quantity < 1) {
                return "{$product->name} is out of stock.";
            }

            if ((int) $product->quantity < $quantity) {
                return "Only {$product->quantity} item(s) available for {$product->name}.";
            }
        }

        return null;
    }
}
