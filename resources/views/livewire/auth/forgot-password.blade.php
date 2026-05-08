<?php

use App\Models\EmailTemplate;
use App\Models\SmtpSetting;
use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Throwable;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    public function sendResetLink(): void
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();

        if (! $user) {
            $this->addError('email', 'We could not find a user with that email address.');
            return;
        }

        try {
            $this->applyDynamicMailConfig();

            $token = Password::broker()->createToken($user);
            $resetLink = route('password.reset', ['token' => $token, 'email' => $user->email]);

            $template = EmailTemplate::where('key', 'forgot_password')->where('is_active', true)->first();
            $subject = $template?->subject ?: 'Reset Your AceStore Password';
            $body = $this->renderTemplate(
                $template?->body ?: "Hello {{name}},\n\nUse this link to reset your password:\n{{reset_link}}",
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'reset_link' => $resetLink,
                ]
            );

            Mail::raw($body, function ($message) use ($user, $subject) {
                $message->to($user->email)->subject($subject);
            });

            session()->flash('status', 'Password reset link sent to your email.');
        } catch (Throwable $exception) {
            report($exception);
            $this->addError('email', 'Unable to send reset email right now.');
        }
    }

    protected function applyDynamicMailConfig(): void
    {
        $settings = SmtpSetting::where('is_active', true)->latest('id')->first();

        if (! $settings) {
            return;
        }

        $scheme = in_array($settings->mail_encryption, ['ssl', 'smtps'], true) ? 'smtps' : 'smtp';

        Config::set('mail.default', $settings->mail_mailer ?: 'smtp');
        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.host', $settings->mail_host);
        Config::set('mail.mailers.smtp.port', $settings->mail_port);
        Config::set('mail.mailers.smtp.username', $settings->mail_username);
        Config::set('mail.mailers.smtp.password', $settings->mail_password);
        Config::set('mail.mailers.smtp.scheme', $scheme);
        Config::set('mail.mailers.smtp.encryption', null);
        Config::set('mail.from.address', $settings->mail_from_address);
        Config::set('mail.from.name', $settings->mail_from_name);

        if (method_exists(app('mail.manager'), 'forgetMailers')) {
            app('mail.manager')->forgetMailers();
        }
    }

    protected function renderTemplate(string $template, array $data): string
    {
        foreach ($data as $key => $value) {
            $template = str_replace('{{' . $key . '}}', (string) $value, $template);
        }

        return $template;
    }
}; ?>

<div class="min-vh-100 d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h4 fw-bold mb-2">Forgot Password</h2>
                        <p class="text-muted">Enter your account email, we will send a reset link.</p>

                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif

                        <form wire:submit.prevent="sendResetLink" class="d-grid gap-3">
                            <div>
                                <label for="email" class="form-label fw-semibold">Email address</label>
                                <input wire:model="email" id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-dark btn-lg">Send Reset Link</button>
                        </form>

                        <p class="text-muted mt-3 mb-0">
                            <a href="{{ route('login') }}" wire:navigate class="text-decoration-none fw-semibold">Back to login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
