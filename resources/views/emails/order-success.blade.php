<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Successful</title>
</head>
<body style="margin:0; padding:0; background:#f3f4f6; font-family: Inter, Arial, sans-serif; color:#0f172a;">
    <div style="max-width:600px; margin:24px auto; background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 12px rgba(15,23,42,.06);">
        <div style="background:#16a34a; color:#ffffff; padding:28px 24px; text-align:center;">
            <h1 style="margin:0; font-size:22px;">Order placed successfully</h1>
            <p style="margin:8px 0 0; opacity:.9;">Order #{{ $orderId }} • {{ $orderDate }}</p>
        </div>

        <div style="padding:24px;">
            <p style="margin:0 0 12px;">Hi {{ $name }},</p>
            <p style="margin:0 0 16px; color:#334155;">Thanks for shopping with AceStore. We've received your order and our team is preparing it for shipment. You'll get another update as soon as it's on the way.</p>

            <h3 style="margin:24px 0 12px; font-size:16px;">Order summary</h3>
            {!! $itemsTable !!}

            <table style="width:100%; margin-top:12px; border-top:1px solid #e5e7eb; padding-top:12px;">
                <tr>
                    <td style="padding:6px 0; color:#64748b;">Subtotal</td>
                    <td style="padding:6px 0; text-align:right;">₹{{ $orderTotal }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0; font-weight:700; font-size:16px;">Total paid</td>
                    <td style="padding:6px 0; text-align:right; font-weight:700; font-size:16px;">₹{{ $orderTotal }}</td>
                </tr>
            </table>

            <h3 style="margin:24px 0 8px; font-size:16px;">Shipping to</h3>
            <p style="margin:0; color:#334155; line-height:1.6;">
                {{ $fullName }}<br>
                {{ $shippingAddress }}<br>
                {{ $city }} {{ $postalCode }}<br>
                {{ $phone }}
            </p>

            <p style="margin:24px 0 0; color:#64748b; font-size:13px;">
                Payment method: <strong style="color:#0f172a;">{{ $paymentMethod }}</strong>
            </p>

            <hr style="border:0; border-top:1px solid #e5e7eb; margin:24px 0;">

            <p style="font-size:13px; color:#64748b;">Need help? Reply to this email and our team will get back to you within 24 hours.</p>
            <p style="font-size:13px; color:#64748b; margin-bottom:0;">— AceStore Team</p>
        </div>
    </div>
</body>
</html>
