<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Mail\OrderSuccessMail;
use App\Models\Order;
use App\Models\User;
use App\Services\SmtpConfigService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function __construct(private readonly SmtpConfigService $smtp) {}
    public function sendOrderPlaced(Order $order): bool
    {
        return $this->dispatch(
            recipient: $this->recipientFor($order),
            mailable: new OrderPlacedMail($order),
            context: ['order_id' => $order->id, 'type' => 'order_placed'],
        );
    }
    public function sendOrderSuccess(Order $order): bool
    {
        return $this->dispatch(
            recipient: $this->recipientFor($order),
            mailable: new OrderSuccessMail($order),
            context: ['order_id' => $order->id, 'type' => 'order_success'],
        );
    }
    public function resendOrderSuccess(int $orderId): RedirectResponse
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($orderId);

        $sent = $this->sendOrderSuccess($order);

        return back()->with(
            $sent ? 'success' : 'error',
            $sent ? 'Order success email resent.' : 'Could not send email. Check the logs for details.'
        );
    }
    public function sendTest(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $this->smtp->apply();
            Mail::raw('This is a test email from AceStore.', function ($message) use ($data) {
                $message->to($data['email'])->subject('AceStore SMTP test');
            });

            return back()->with('success', "Test email sent to {$data['email']}.");
        } catch (\Throwable $e) {
            Log::warning('Test email failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'Test email failed: '.$e->getMessage());
        }
    }
    private function recipientFor(Order $order): ?string
    {
        return $order->email ?: optional($order->user)->email;
    }
    private function dispatch(?string $recipient, $mailable, array $context = []): bool
    {
        if (! $recipient) {
            Log::info('Email skipped — no recipient', $context);

            return false;
        }

        try {
            $this->smtp->apply();
            Mail::to($recipient)->send($mailable);

            return true;
        } catch (\Throwable $e) {
            Log::warning('Email send failed', $context + ['error' => $e->getMessage()]);

            return false;
        }
    }
}
