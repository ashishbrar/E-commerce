<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use App\Models\EmailTemplate;
use App\Models\SmtpSetting;
use App\Models\User;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        if (! Auth::validate(['email' => $this->email, 'password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $user = User::where('email', $this->email)->first();

        if ($user && $user->status === 'Inactive') {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Your account is inactive. Please contact support.',
            ]);
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        session()->regenerate();
        session()->flash('success', 'Login successfully.');

        $user = Auth::user();

        if ($user) {
            try {
                $this->applyDynamicMailConfig();
                $template = EmailTemplate::where('key', 'login_alert')->where('is_active', true)->first();
                $subject = $template?->subject ?: 'AceStore Login Alert';
                $body = $this->renderTemplate(
                    $template?->body ?: "Hello {{name}},\n\nYour account was logged in successfully.",
                    [
                        'name' => $user->name,
                        'email' => $user->email,
                    ]
                );

                Mail::raw($body, function ($message) use ($user, $subject) {
                    $message->to($user->email)->subject($subject);
                });
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        if ($user && $user->role === 'Customer') {
            $this->redirect(route('home'), navigate: true);
        } else {
            $this->redirect(route('dashboard'), navigate: true);
        }
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
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
        <div class="row justify-content-center align-items-stretch g-0 shadow-lg overflow-hidden bg-white auth-card mx-auto">
            <div class="col-lg-5 d-none d-lg-flex auth-visual text-white p-5 flex-column justify-content-between">
                <a href="{{ route('home') }}" wire:navigate class="d-inline-flex align-items-center gap-2 text-white text-decoration-none fw-bold fs-4">
                    <span class="auth-brand-mark d-inline-flex align-items-center justify-content-center rounded-3 bg-white text-dark">A</span>
                    AceStore
                </a>

                <div>
                    <span class="badge rounded-pill text-bg-light text-dark mb-3">Customer access</span>
                    <h1 class="display-5 fw-bold mb-3">Welcome back.</h1>
                    <p class="text-white-50 mb-0 fs-5">Log in to manage your cart, wishlist, orders, and checkout faster.</p>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 rounded-4 p-3">
                            <i class="bi bi-bag-check fs-4"></i>
                            <span class="fw-semibold">Synced cart and wishlist</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 rounded-4 p-3">
                            <i class="bi bi-shield-check fs-4"></i>
                            <span class="fw-semibold">Secure account access</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 rounded-4 p-3">
                            <i class="bi bi-truck fs-4"></i>
                            <span class="fw-semibold">Track shopping activity</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="p-4 p-md-5">
                    <div class="mb-4">
                        <a href="{{ route('home') }}" wire:navigate class="text-decoration-none text-muted small fw-semibold">
                            <i class="bi bi-arrow-left me-1"></i> Back to store
                        </a>
                    </div>

                    <div class="mb-4 pb-2">
                        <span class="badge text-bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-3">Login</span>
                        <h2 class="display-6 fw-bold mb-2">Sign in to your account</h2>
                        <p class="text-muted mb-0">Use your email and password to continue.</p>
                    </div>

                    <form wire:submit.prevent="login" class="d-grid gap-3">
                        <div>
                            <label for="email" class="form-label fw-semibold">Email address</label>
                            <input wire:model="email" id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" required autofocus autocomplete="email" placeholder="you@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <div class="d-flex justify-content-between gap-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" wire:navigate class="small fw-semibold text-decoration-none">Forgot password?</a>
                                @endif
                            </div>
                            <input wire:model="password" id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Enter password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-check">
                            <input wire:model="remember" id="remember" type="checkbox" class="form-check-input">
                            <label for="remember" class="form-check-label text-muted">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100" wire:loading.attr="disabled">
                            <span wire:loading.remove>Log in</span>
                            <span wire:loading>Logging in...</span>
                        </button>
                    </form>

                    @if (Route::has('register'))
                        <p class="text-center text-muted mt-4 mb-0">
                            Do not have an account?
                            <a href="{{ route('register') }}" wire:navigate class="fw-semibold text-decoration-none">Create one</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
