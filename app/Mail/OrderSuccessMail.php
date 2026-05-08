<?php

namespace App\Mail;

use App\Models\Order;
use App\Services\EmailTemplateService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    private const TEMPLATE_KEY = 'order_success';

    private string $renderedSubject = 'Your AceStore order is successful';
    private ?string $renderedBody = null;

    public function __construct(public Order $order)
    {
        $this->order->loadMissing(['items.product', 'user']);

        $template = app(EmailTemplateService::class)->render(
            self::TEMPLATE_KEY,
            $this->placeholders()
        );

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
        if ($this->renderedBody !== null && $this->renderedBody !== '') {
            return new Content(htmlString: $this->renderedBody);
        }

        return new Content(
            view: 'emails.order-success',
            with: $this->viewData()
        );
    }

    /**
     * Placeholders used by the DB-stored email template (`{{ key }}` tokens).
     *
     * @return array<string, string>
     */
    private function placeholders(): array
    {
        $data = $this->viewData();

        return [
            'name'             => $data['name'],
            'order_id'         => $data['orderId'],
            'order_date'       => $data['orderDate'],
            'order_total'      => $data['orderTotal'],
            'full_name'        => $data['fullName'],
            'shipping_address' => $data['shippingAddress'],
            'city'             => $data['city'],
            'postal_code'      => $data['postalCode'],
            'phone'            => $data['phone'],
            'payment_method'   => $data['paymentMethod'],
            'items_table'      => $data['itemsTable'],
        ];
    }

    /**
     * Variables passed to the Blade view fallback.
     *
     * @return array<string, string>
     */
    private function viewData(): array
    {
        return [
            'name'            => $this->order->full_name ?: optional($this->order->user)->name ?: 'Customer',
            'orderId'         => str_pad((string) $this->order->id, 5, '0', STR_PAD_LEFT),
            'orderDate'       => optional($this->order->created_at)->format('d M Y, H:i') ?: now()->format('d M Y, H:i'),
            'orderTotal'      => number_format((float) $this->order->total, 2),
            'fullName'        => $this->order->full_name ?: '',
            'shippingAddress' => $this->order->address ?: '',
            'city'            => $this->order->city ?: '',
            'postalCode'      => $this->order->postal_code ?: '',
            'phone'           => $this->order->phone ?: '',
            'paymentMethod'   => ucwords(str_replace('_', ' ', (string) $this->order->payment_method)),
            'itemsTable'      => $this->buildItemsTable(),
        ];
    }

    private function buildItemsTable(): string
    {
        $rows = '';
        foreach ($this->order->items as $item) {
            $name = e(optional($item->product)->name ?? 'Product');
            $qty = (int) $item->quantity;
            $subtotal = number_format((float) $item->price, 2);
            $rows .= '<tr>'
                .'<td style="padding:10px 8px; border-bottom:1px solid #f1f5f9;">'.$name.'</td>'
                .'<td style="padding:10px 8px; border-bottom:1px solid #f1f5f9; text-align:center;">'.$qty.'</td>'
                .'<td style="padding:10px 8px; border-bottom:1px solid #f1f5f9; text-align:right;">₹'.$subtotal.'</td>'
                .'</tr>';
        }

        return '<table style="width:100%; border-collapse:collapse;">'
            .'<thead><tr>'
            .'<th style="text-align:left; padding:8px; border-bottom:1px solid #e5e7eb; font-size:12px; color:#64748b; text-transform:uppercase;">Product</th>'
            .'<th style="text-align:center; padding:8px; border-bottom:1px solid #e5e7eb; font-size:12px; color:#64748b; text-transform:uppercase;">Qty</th>'
            .'<th style="text-align:right; padding:8px; border-bottom:1px solid #e5e7eb; font-size:12px; color:#64748b; text-transform:uppercase;">Subtotal</th>'
            .'</tr></thead>'
            .'<tbody>'.$rows.'</tbody>'
            .'</table>';
    }
}
