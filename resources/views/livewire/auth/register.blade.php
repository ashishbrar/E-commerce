<?php

use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\SmtpSetting;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'Customer';

        event(new Registered(($user = User::create($validated))));

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('Customer');
        }

        try {
            $this->applyDynamicMailConfig();
            $template = EmailTemplate::where('key', 'registration_welcome')->where('is_active', true)->first();
            $subject = $template?->subject ?: 'Welcome to AceStore';
            $body = $this->renderTemplate(
                $template?->body ?: "Hello {{name}},\n\nYour account has been created.",
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $this->password,
                    'login_url' => route('login'),
                ]
            );

            Mail::raw($body, function ($message) use ($user, $subject) {
                $message->to($user->email)->subject($subject);
            });
        } catch (Throwable $exception) {
            report($exception);
        }

        Auth::login($user);
        session()->flash('success', 'Account created successfully.');

        $this->redirect(route('home'), navigate: true);
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
                    <span class="badge rounded-pill text-bg-light text-dark mb-3">Join AceStore</span>
                    <h1 class="display-5 fw-bold mb-3">Create your shopping account.</h1>
                    <p class="text-white-50 mb-0 fs-5">Save your wishlist, keep your cart synced, and checkout with fewer steps.</p>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 rounded-4 p-3">
                            <i class="bi bi-heart fs-4"></i>
                            <span class="fw-semibold">Keep favorite products saved</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 rounded-4 p-3">
                            <i class="bi bi-bag fs-4"></i>
                            <span class="fw-semibold">Faster checkout experience</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 rounded-4 p-3">
                            <i class="bi bi-person-check fs-4"></i>
                            <span class="fw-semibold">Customer account access</span>
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
                        <span class="badge text-bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-3">Register</span>
                        <h2 class="display-6 fw-bold mb-2">Create an account</h2>
                        <p class="text-muted mb-0">Enter your details to get started.</p>
                    </div>

                    <x-auth-session-status class="text-center" :status="session('status')" />

                    <form wire:submit.prevent="register" class="d-grid gap-3">
                        <div>
                            <label for="name" class="form-label fw-semibold">Full name</label>
                            <input wire:model="name" id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" required autofocus autocomplete="name" placeholder="Your full name">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="email" class="form-label fw-semibold">Email address</label>
                            <input wire:model="email" id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" required autocomplete="email" placeholder="you@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input wire:model="password" id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="Create password">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="form-label fw-semibold">Confirm password</label>
                            <input wire:model="password_confirmation" id="password_confirmation" type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror" required autocomplete="new-password" placeholder="Repeat password">
                            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100" wire:loading.attr="disabled">
                            <span wire:loading.remove>Create account</span>
                            <span wire:loading>Creating account...</span>
                        </button>
                    </form>

                    <p class="text-center text-muted mt-4 mb-0">
                        Already have an account?
                        <a href="{{ route('login') }}" wire:navigate class="fw-semibold text-decoration-none">Log in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
