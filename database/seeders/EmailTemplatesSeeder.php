<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Registration Welcome',
                'key' => 'registration_welcome',
                'subject' => 'Welcome to AceStore',
                'body' => "Hello {{name}},\n\nYour AceStore account has been created successfully.\n\nUsername: {{email}}\nPassword: {{password}}\n\nYou can log in from: {{login_url}}\n\nThanks,\nAceStore Team",
            ],
            [
                'name' => 'Login Alert',
                'key' => 'login_alert',
                'subject' => 'AceStore Login Alert',
                'body' => "Hello {{name}},\n\nYour account was logged in successfully.\nIf this was not you, reset your password immediately.\n\nRegards,\nAceStore Team",
            ],
            [
                'name' => 'Forgot Password Reset Link',
                'key' => 'forgot_password',
                'subject' => 'Reset Your AceStore Password',
                'body' => "Hello {{name}},\n\nWe received a request to reset your password.\nUse the link below to set a new password:\n{{reset_link}}\n\nIf you did not request this, you can ignore this email.\n\nRegards,\nAceStore Team",
            ],
            [
                'name' => 'Password Reset Success',
                'key' => 'password_reset_success',
                'subject' => 'Your AceStore Password Was Reset',
                'body' => "Hello {{name}},\n\nYour password has been reset successfully.\nYou can log in here: {{login_url}}\n\nRegards,\nAceStore Team",
            ],
            [
                'name' => 'Order Success',
                'key' => 'order_success',
                'subject' => 'Order #{{order_id}} placed successfully',
                'body' => <<<'HTML'
<div style="font-family: Inter, Arial, sans-serif; color: #0f172a; max-width: 600px; margin: 0 auto;">
    <div style="background: #16a34a; color: #fff; padding: 24px; border-radius: 12px 12px 0 0; text-align: center;">
        <h1 style="margin: 0; font-size: 22px;">Order placed successfully</h1>
        <p style="margin: 8px 0 0; opacity: .9;">Order #{{order_id}} • {{order_date}}</p>
    </div>

    <div style="background: #fff; border: 1px solid #e5e7eb; border-top: 0; padding: 24px; border-radius: 0 0 12px 12px;">
        <p>Hi {{name}},</p>
        <p>Thanks for shopping with AceStore. We've received your order and our team is preparing it for shipment. You'll get another update as soon as it's on the way.</p>

        <h3 style="margin: 24px 0 12px; font-size: 16px;">Order summary</h3>
        {{items_table}}

        <table style="width: 100%; margin-top: 12px; border-top: 1px solid #e5e7eb; padding-top: 12px;">
            <tr>
                <td style="padding: 6px 0; color: #64748b;">Subtotal</td>
                <td style="padding: 6px 0; text-align: right;">₹{{order_total}}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: 700; font-size: 16px;">Total paid</td>
                <td style="padding: 6px 0; text-align: right; font-weight: 700; font-size: 16px;">₹{{order_total}}</td>
            </tr>
        </table>

        <h3 style="margin: 24px 0 8px; font-size: 16px;">Shipping to</h3>
        <p style="margin: 0; color: #334155; line-height: 1.6;">
            {{full_name}}<br>
            {{shipping_address}}<br>
            {{city}} {{postal_code}}<br>
            {{phone}}
        </p>

        <p style="margin: 24px 0 0; color: #64748b; font-size: 13px;">Payment method: <strong style="color: #0f172a;">{{payment_method}}</strong></p>

        <hr style="border: 0; border-top: 1px solid #e5e7eb; margin: 24px 0;">
        <p style="font-size: 13px; color: #64748b;">— AceStore Team</p>
    </div>
</div>
HTML,
            ],
            [
                'name' => 'Order Placed Confirmation',
                'key' => 'order_placed',
                'subject' => 'Your AceStore order #{{order_id}} is confirmed',
                'body' => <<<'HTML'
<div style="font-family: Inter, Arial, sans-serif; color: #0f172a; max-width: 600px; margin: 0 auto;">
    <div style="background: #0f172a; color: #fff; padding: 24px; border-radius: 12px 12px 0 0;">
        <h1 style="margin: 0; font-size: 22px;">Thanks for your order, {{name}}!</h1>
        <p style="margin: 8px 0 0; opacity: .8;">Order #{{order_id}} placed on {{order_date}}</p>
    </div>

    <div style="background: #fff; border: 1px solid #e5e7eb; border-top: 0; padding: 24px; border-radius: 0 0 12px 12px;">
        <p>We've received your order and are getting it ready. You'll get another email once it ships.</p>

        <h3 style="margin: 24px 0 12px; font-size: 16px;">Order summary</h3>
        {{items_table}}

        <table style="width: 100%; margin-top: 12px; border-top: 1px solid #e5e7eb; padding-top: 12px;">
            <tr>
                <td style="padding: 6px 0; color: #64748b;">Subtotal</td>
                <td style="padding: 6px 0; text-align: right;">₹{{order_total}}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0; font-weight: 700; font-size: 16px;">Total paid</td>
                <td style="padding: 6px 0; text-align: right; font-weight: 700; font-size: 16px;">₹{{order_total}}</td>
            </tr>
        </table>

        <h3 style="margin: 24px 0 8px; font-size: 16px;">Shipping to</h3>
        <p style="margin: 0; color: #334155; line-height: 1.6;">
            {{full_name}}<br>
            {{shipping_address}}<br>
            {{city}} {{postal_code}}<br>
            {{phone}}
        </p>

        <p style="margin: 24px 0 0; color: #64748b; font-size: 13px;">Payment method: <strong style="color: #0f172a;">{{payment_method}}</strong></p>

        <hr style="border: 0; border-topEasy returns: 1px solid #e5e7eb; margin: 24px 0;">

        <p style="font-size: 13px; color: #64748b;">Need help? Reply to this email and our team will get back to you within 24 hours.</p>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 0;">— AceStore Team</p>
    </div>
</div>
HTML,
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['key' => $template['key']],
                array_merge($template, ['is_active' => true])
            );
        }
    }
}
