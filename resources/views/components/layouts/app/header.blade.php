<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('patxiai-patxi::partials.head')
    </head>
    <body class="min-h-screen bg-zinc-50 dark:bg-zinc-900 antialised"
        @keydown.window.prevent.meta.k="window.Livewire.navigate('{{ route('chat') }}')"
        >
        <flux:header sticky class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <x-patxiai-patxi::app-logo href="{{ route('dashboard') }}" wire:navigate />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
                <flux:navbar.item icon="chat-bubble-left" :href="route('chat')" :current="request()->routeIs('chat')" wire:navigate>
                    {{ __('Chat') }}
                </flux:navbar.item>
                <flux:navbar.item icon="sparkles" :href="route('agents')" :current="request()->routeIs('agents')" wire:navigate>
                    {{ __('Agents') }}
                </flux:navbar.item>
                <flux:navbar.item icon="squares-2x2" :href="route('applications')" :current="request()->routeIs('applications')" wire:navigate>
                    {{ __('Applications') }}
                </flux:navbar.item>
                <flux:navbar.item icon="code-bracket-square" :href="route('integrations')" :current="request()->routeIs('integrations')" wire:navigate>
                    {{ __('Integrations') }}
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:tooltip :content="__('Documentation')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open"
                        href="https://laravel.com/docs/starter-kits#livewire"
                        target="_blank"
                        :label="__('Documentation')"
                    />
                </flux:tooltip>
            </flux:navbar>

            <x-patxiai-patxi::desktop-user-menu />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-patxiai-patxi::app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard')  }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="chat-bubble-left" :href="route('chat')" :current="request()->routeIs('chat')" wire:navigate>
                        {{ __('Chat') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="sparkles" :href="route('agents')" :current="request()->routeIs('agents')" wire:navigate>
                        {{ __('Agents') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="squares-2x2" :href="route('applications')" :current="request()->routeIs('applications')" wire:navigate>
                        {{ __('Applications') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="code-bracket-square" :href="route('integrations')" :current="request()->routeIs('integrations')" wire:navigate>
                        {{ __('Integrations') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="book-open" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
