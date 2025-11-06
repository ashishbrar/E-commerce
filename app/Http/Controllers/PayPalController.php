<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Models\PaypalCredential;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    public function payWithPayPal(Request $request)
    {

        $request->validate([
            'product_ids'   => 'required|array',
            'product_ids.*' => 'required|exists:products,id',
            'full_name'     => 'required|string',
            'email'         => 'required|email',
            'phone'         => 'required|string',
            'address'       => 'required|string',
            'city'          => 'required|string',
            'postal_code'   => 'required|string',
        ]);

        $productIds = $request->input('product_ids', []);
        if (!is_array($productIds)) {
            $productIds = [$productIds];
        }

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0.0;

        foreach ($productIds as $id) {
            $product = $products->get($id);
            if (! $product) continue;
            $qty = intval($request->input("quantity.{$id}", 1));
            if ($qty < 1) $qty = 1;

            $items[] = [
                'name' => substr($product->name, 0, 127),
                'unit_amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($product->price, 2, '.', '')
                ],
                'quantity' => (string) $qty,
            ];

            $total += $product->price * $qty;
        }

        $totalValue = number_format($total, 2, '.', '');

        $settings = PaypalCredential::first();

        $provider = new PayPalClient;

        $provider->setApiCredentials([
            'mode' => $settings->sandbox == 1 ? 'sandbox' : 'live',
            'payment_action' => 'Sale',  
            'currency' => 'USD',          
            'notify_url' => '', 
            'locale'          => 'en_US',
            'validate_ssl'    => true, 
            'sandbox' => [
                'client_id'     => $settings->paypal_client_id,
                'client_secret' => $settings->paypal_secret,
                'app_id'        => '',
            ],
        
            'live' => [
                'client_id'     => $settings->paypal_client_id,
                'client_secret' => $settings->paypal_secret,
                'app_id'        => '',
            ],
        ]);
        
        $provider->getAccessToken();

        $orderData = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $totalValue,
                    "breakdown" => [
                        "item_total" => [
                            "currency_code" => "USD",
                            "value" => $totalValue
                        ]
                    ]
                ],
                "items" => $items
            ]],
            "application_context" => [
                "cancel_url" => route('paypal.cancel'),
                "return_url" => route('paypal.success') . '?' . http_build_query($request->all())
            ]
        ];

        $order = $provider->createOrder($orderData);

        if (isset($order['links'])) {
            foreach ($order['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect($link['href']);
                }
            }
        }

        return redirect()->route('home')->with('error', 'Unable to create PayPal order.');
    }

    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $token = $request->get('token') ?? $request->get('paymentId') ?? null;
        $response = $provider->capturePaymentOrder($request->token ?? $token);

        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            $productIds = $request->input('product_ids', []);
            if (!is_array($productIds)) {
                $productIds = [$productIds];
            }

            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $total = 0;
            foreach ($productIds as $id) {
                $product = $products->get($id);
                if (! $product) continue;
                $qty = intval($request->input("quantity.{$id}", 1));
                if ($qty < 1) $qty = 1;
                $total += $product->price * $qty;
            }

            $order = Order::create([
                'user_id'        => Auth::id(),
                'total'          => $total,
                'address'        => $request->address,
                'status'         => 'complete',
                'full_name'      => $request->full_name,
                'email'          => $request->email,
                'phone'          => $request->phone,
                'city'           => $request->city,
                'postal_code'    => $request->postal_code,
                'payment_method' => 'paypal',
            ]);

            foreach ($productIds as $id) {
                $product = $products->get($id);
                if (! $product) continue;
                $qty = intval($request->input("quantity.{$id}", 1));
                if ($qty < 1) $qty = 1;

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $qty,
                    'address'    => $request->address,
                    'price'      => $product->price * $qty,
                ]);
            }

            if (Auth::check()) {
                Cart::where('user_id', Auth::id())
                    ->whereIn('product_id', $productIds)
                    ->delete();
            }

            return redirect()->route('home')->with('success', 'Payment completed successfully!');
        }

        return redirect()->route('home')->with('error', 'Payment failed.');
    }

    public function paymentCancel()
    {
        return redirect()->route('home')->with('error', 'Payment was cancelled.');
    }
}
