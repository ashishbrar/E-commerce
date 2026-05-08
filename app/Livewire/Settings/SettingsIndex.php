<?php

namespace App\Livewire\Settings;

use App\Models\PaypalCredential;
use App\Models\SmtpSetting;
use App\Services\SmtpConfigService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Throwable;

class SettingsIndex extends Component
{
    public string $mail_mailer = 'smtp';
    public string $mail_host = '';
    public int $mail_port = 587;
    public ?string $mail_username = null;
    public ?string $mail_password = null;
    public ?string $mail_encryption = 'tls';
    public string $mail_from_address = '';
    public string $mail_from_name = 'Ace Admin';
    public bool $is_active = true;

    public string $paypal_method = 'sandbox';
    public string $client_id = '';
    public ?string $client_secret = null;

    public ?string $test_email = null;

    public function mount(): void
    {
        $smtp = SmtpSetting::latest('id')->first();

        if ($smtp) {
            $this->fill($smtp->only([
                'mail_mailer',
                'mail_host',
                'mail_port',
                'mail_username',
                'mail_password',
                'mail_encryption',
                'mail_from_address',
                'mail_from_name',
                'is_active',
            ]));
        }

        $paypal = PaypalCredential::latest('id')->first();

        if ($paypal) {
            $this->fill($paypal->only([
                'paypal_method',
                'client_id',
                'client_secret',
            ]));
        }

        $this->test_email = auth()->user()?->email;
    }

    public function saveSmtp(): void
    {
        $validated = $this->validate([
            'mail_mailer' => ['required', Rule::in(['smtp'])],
            'mail_host' => ['required', 'string', 'max:255'],
            'mail_port' => ['required', 'integer', 'min:1', 'max:65535'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string'],
            'mail_encryption' => ['nullable', Rule::in(['tls', 'ssl', ''])],
            'mail_from_address' => ['required', 'email', 'max:255'],
            'mail_from_name' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ]);

        $validated['mail_encryption'] = $validated['mail_encryption'] ?: null;

        $smtp = SmtpSetting::latest('id')->first();

        if ($smtp) {
            $smtp->update($validated);
        } else {
            SmtpSetting::create($validated);
        }

        app(SmtpConfigService::class)->apply();

        $this->dispatch('flash', type: 'success', message: 'SMTP settings saved successfully.');
    }

    public function savePaypal(): void
    {
        $validated = $this->validate([
            'paypal_method' => ['required', Rule::in(['sandbox', 'live'])],
            'client_id' => ['required', 'string', 'max:255'],
            'client_secret' => ['required', 'string'],
        ]);

        $paypal = PaypalCredential::latest('id')->first();

        if ($paypal) {
            $paypal->update($validated);
        } else {
            PaypalCredential::create($validated);
        }

        $this->dispatch('flash', type: 'success', message: 'PayPal credentials saved successfully.');
    }

    public function sendTestEmail(): void
    {
        $this->validate([
            'test_email' => ['required', 'email', 'max:255'],
        ]);

        app(SmtpConfigService::class)->apply();

        try {
            Mail::raw('This is a test email from Ace Admin SMTP settings.', function ($message) {
                $message->to($this->test_email)->subject('Ace Admin SMTP Test');
            });

            $this->dispatch('flash', type: 'success', message: 'Test email sent successfully.');
        } catch (Throwable $exception) {
            $this->dispatch('flash', type: 'error', message: 'Test email failed: ' . $exception->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.settings.settings-index');
    }
}
