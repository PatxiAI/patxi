<x-patxiai-patxi::layouts.auth :title="__('Forgot password')">
    <div class="flex flex-col gap-6">
        <x-patxiai-patxi::auth-header :title="__('Forgot your password?')" :description="__('Enter your email and we\'ll send you a reset link')" />

        <x-patxiai-patxi::auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Email password reset link') }}
            </flux:button>
        </form>

        <div class="text-sm text-center text-zinc-600 dark:text-zinc-400">
            <flux:link :href="route('login')" wire:navigate>{{ __('Back to log in') }}</flux:link>
        </div>
    </div>
</x-patxiai-patxi::layouts.auth>
