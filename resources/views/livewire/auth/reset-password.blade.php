<?php

use App\Models\EmailTemplate;
use App\Models\SmtpSetting;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use Throwable;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = (string) request()->query('email', '');
    }

    public function resetPassword(): void
    {
        $validated = $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $validated,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            $this->addError('email', __($status));
            return;
        }

        $user = User::where('email', $this->email)->first();

        if ($user) {
            try {
                $this->applyDynamicMailConfig();
                $template = EmailTemplate::where('key', 'password_reset_success')->where('is_active', true)->first();
                $subject = $template?->subject ?: 'Your AceStore Password Was Reset';
                $body = $this->renderTemplate(
                    $template?->body ?: "Hello {{name}},\n\nYour password has been reset successfully.",
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                        'login_url' => route('login'),
                    ]
                );

                Mail::raw($body, function ($message) use ($user, $subject) {
                    $message->to($user->email)->subject($subject);
                });
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        session()->flash('status', 'Password reset successful. Please login.');
        $this->redirect(route('login'), navigate: true);
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
                        <h2 class="h4 fw-bold mb-2">Reset Password</h2>
                        <p class="text-muted">Set a new password for your account.</p>

                        <form wire:submit.prevent="resetPassword" class="d-grid gap-3">
                            <input type="hidden" wire:model="token">

                            <div>
                                <label for="email" class="form-label fw-semibold">Email address</label>
                                <input wire:model="email" id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="password" class="form-label fw-semibold">New password</label>
                                <input wire:model="password" id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm password</label>
                                <input wire:model="password_confirmation" id="password_confirmation" type="password" class="form-control form-control-lg" required>
                            </div>

                            <button type="submit" class="btn btn-dark btn-lg">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
