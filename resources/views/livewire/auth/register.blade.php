<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        } elseif (property_exists($user, 'role')) {
            $user->role = 'Customer';
            $user->save();
        }

        Auth::login($user);
        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div style="max-width: 420px; margin: 3rem auto; padding: 0 1rem;">
    <div style="background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.07); border: 1px solid #e5e7eb;">
        <div style="padding: 2rem;">
            <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

            <x-auth-session-status style="text-align:center;margin-bottom:1.2rem;" :status="session('status')" />

            <form method="POST" wire:submit="register" novalidate>
                <div style="margin-bottom: 1rem;">
                    <label for="name" style="display:block;font-weight:600;margin-bottom:0.5rem;">{{ __('Name') }}</label>
                    <input
                        type="text"
                        id="name"
                        wire:model="name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="{{ __('Full name') }}"
                        style="width:100%;padding:0.5rem;border-radius:4px;border:1px solid #ccc;"
                    >
                    @error('name')
                        <div style="color:#e3342f;font-size:.93em;margin-top:0.35rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="email" style="display:block;font-weight:600;margin-bottom:0.5rem;">{{ __('Email address') }}</label>
                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        required
                        autocomplete="email"
                        placeholder="email@example.com"
                        style="width:100%;padding:0.5rem;border-radius:4px;border:1px solid #ccc;"
                    >
                    @error('email')
                        <div style="color:#e3342f;font-size:.93em;margin-top:0.35rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="password" style="display:block;font-weight:600;margin-bottom:0.5rem;">{{ __('Password') }}</label>
                    <input
                        type="password"
                        id="password"
                        wire:model="password"
                        required
                        autocomplete="new-password"
                        placeholder="{{ __('Password') }}"
                        style="width:100%;padding:0.5rem;border-radius:4px;border:1px solid #ccc;"
                    >
                    @error('password')
                        <div style="color:#e3342f;font-size:.93em;margin-top:0.35rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label for="password_confirmation" style="display:block;font-weight:600;margin-bottom:0.5rem;">{{ __('Confirm password') }}</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        wire:model="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="{{ __('Confirm password') }}"
                        style="width:100%;padding:0.5rem;border-radius:4px;border:1px solid #ccc;"
                    >
                    @error('password_confirmation')
                        <div style="color:#e3342f;font-size:.93em;margin-top:0.35rem;">{{ $message }}</div>
                    @enderror
                </div>
                <div style="margin-bottom:0.8rem;">
                    <button type="submit" style="width:100%;padding:0.7rem;font-weight:600;color:#fff;background:#007bff;border:none;border-radius:4px;">
                        {{ __('Create account') }}
                    </button>
                </div>
            </form>

            <div style="text-align:center;margin-top:1.2rem;">
                <span style="color:#6c757d;">{{ __('Already have an account?') }}</span>
                <a href="{{ route('login') }}" style="margin-left:0.4rem;color:#007bff;text-decoration:none;font-weight:500;">
                    {{ __('Log in') }}
                </a>
            </div>
        </div>
    </div>
</div>
