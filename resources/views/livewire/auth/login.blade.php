<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
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

        $user = Auth::user();
        if ($user && $user->role === 'Customer') {
            $this->redirect(route('home'), navigate: true);
        } else {
            $this->redirect(route('dashboard'), navigate: true);
            session()->flash('success', 'Login successfully.');
        }
    }


    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>

<div style="max-width: 420px; margin: 3rem auto; padding: 0 1rem;">
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.07); border: 1px solid #e5e7eb;">
        <div style="padding: 2rem;">
            <x-auth-header :title="__('Log in')"/>
            <x-auth-session-status style="text-align:center;margin-bottom:1.2rem;" :status="session('status')" />

            <form method="POST" wire:submit="login" novalidate>
                <div style="margin-bottom: 1rem;">
                    <label for="email" style="display:block;font-weight:600;margin-bottom:0.5rem;">{{ __('Email') }}</label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="email@example.com"
                        style="width:100%;padding:0.5rem;border-radius:4px;border:1px solid #ccc;"
                    >
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="password" style="display:block;font-weight:600;margin-bottom:0.5rem;">{{ __('Password') }}</label>
                    <input
                        type="password"
                        id="password"
                        wire:model="password"
                        required
                        autocomplete="current-password"
                        placeholder="{{ __('Password') }}"
                        style="width:100%;padding:0.5rem;border-radius:4px;border:1px solid #ccc;"
                    >
                </div>

                <div style="display:flex;align-items:center;margin-bottom:1rem;">
                    <input
                        type="checkbox"
                        id="remember"
                        wire:model="remember"
                        style="margin-right:0.5rem;"
                    >
                    <label for="remember">
                        {{ __('Remember me') }}
                    </label>
                </div>

                <div style="margin-bottom:0.8rem;">
                    <button type="submit" style="width:100%;padding:0.7rem;font-weight:600;color:#fff;background:#007bff;border:none;border-radius:4px;">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>

            @if (Route::has('register'))
                <div style="text-align:center;margin-top:1.2rem;">
                    <span style="color:#6c757d;">{{ __("Don't have an account?") }}</span>
                    <a href="{{ route('register') }}" style="margin-left:0.4rem;color:#007bff;text-decoration:none;font-weight:500;">
                        {{ __('Sign up') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
