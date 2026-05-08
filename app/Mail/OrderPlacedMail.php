<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    private string $renderedSubject = 'Your AceStore order is confirmed';
    private string $renderedBody = '';

    public function __construct(public Order $order)
    {
        $template = app(EmailTemplateService::class)->render('order_placed', $this->payload());

        if ($template) {
            $this->renderedSubject = $template['subject'];
            $this->renderedBody = $template['body'];
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: $this->renderedSubject);
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->renderedBody !== '' ? $this->renderedBody : $this->fallbackBody(),
        );
    }

    /**
     * Build the placeholder data the template expects.
     *
     * @return array<string, string>
     */
    private function payload(): array
    {
        $this->order->loadMissing(['items.product', 'user']);

        $itemsTable = '<table style="width:100%; border-collapse:collapse;">';
        $itemsTable .= '<thead><tr>'
            .'<th style="text-align:left; padding:8px; border-bottom:1px solid #e5e7eb; font-size:12px; color:#64748b; text-transform:uppercase;">Product</th>'
            .'<th style="text-align:center; padding:8px; border-bottom:1px solid #e5e7eb; font-size:12px; color:#64748b; text-transform:uppercase;">Qty</th>'
            .'<th style="text-align:right; padding:8px; border-bottom:1px solid #e5e7eb; font-size:12px; color:#64748b; text-transform:uppercase;">Subtotal</th>'
            .'</tr></thead><tbody>';

        foreach ($this->order->items as $item) {
            $name = e(optional($item->product)->name ?? 'Product');
            $qty = (int) $item->quantity;
            $subtotal = number_format((float) $item->price, 2);
            $itemsTable .= "<tr>"
                ."<td style=\"padding:10px 8px; border-bottom:1px solid #f1f5f9;\">{$name}</td>"
                ."<td style=\"padding:10px 8px; border-bottom:1px solid #f1f5f9; text-align:center;\">{$qty}</td>"
                ."<td style=\"padding:10px 8px; border-bottom:1px solid #f1f5f9; text-align:right;\">₹{$subtotal}</td>"
                ."</tr>";
        }

        $itemsTable .= '</tbody></table>';

        return [
            'name'             => $this->order->full_name ?: optional($this->order->user)->name ?: 'Customer',
            'order_id'         => str_pad((string) $this->order->id, 5, '0', STR_PAD_LEFT),
            'order_date'       => optional($this->order->created_at)->format('d M Y, H:i') ?: now()->format('d M Y, H:i'),
            'order_total'      => number_format((float) $this->order->total, 2),
            'full_name'        => $this->order->full_name ?: '',
            'shipping_address' => $this->order->address ?: '',
            'city'             => $this->order->city ?: '',
            'postal_code'      => $this->order->postal_code ?: '',
            'phone'            => $this->order->phone ?: '',
            'payment_method'   => ucwords(str_replace('_', ' ', (string) $this->order->payment_method)),
            'items_table'      => $itemsTable,
            'login_url'        => url('/login'),
        ];
    }

    private function fallbackBody(): string
    {
        return '<p>Hello '.e($this->order->full_name ?: 'Customer').',</p>'
            .'<p>Your order #'.$this->order->id.' has been placed successfully.</p>'
            .'<p>Total: ₹'.number_format((float) $this->order->total, 2).'</p>'
            .'<p>— AceStore Team</p>';
    }
}
