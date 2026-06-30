<x-patxiai-patxi::layouts.app.header :title="__('Agent Patxi')">
    <flux:main class="p-0! flex h-full" x-data="{ title: null }" @keydown.window.meta.shift.o.prevent="$dispatch('new-conversation'); title = null">
        <flux:sidebar sticky collapsible="mobile" class="w-84 h-full bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:button
                    icon="plus"
                    variant="primary"
                    color="orange"
                    class="w-full"
                    @click="$dispatch('new-conversation'); title = null"
                >
                    {{ __('New chat') }}
                </flux:button>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <livewire:patxiai-patxi::pages.dashboard.chat.history />
        </flux:sidebar>

        <!-- Chat -->
        <section
            id="chat"
            class="w-full h-full flex flex-col bg-orange-50 dark:bg-zinc-900"
            @conversation-started.window="title = $event.detail.title"
            @conversation-selected.window="title = $event.detail.title"
            @new-conversation.window="title = null"
        >
            <!-- Header -->
            <div class="p-4 flex justify-between gap-x-4 border-b border-zinc-200 dark:border-zinc-700 shrink-0">
                <flux:text
                    variant="strong"
                    class="font-bold"
                    x-text="title || '{{ __('New conversation') }}'"
                >{{ __('New conversation') }}</flux:text>
            </div>
            <!-- /Header -->

            <livewire:patxiai-patxi::pages.dashboard.chat.chat />
        </section>
        <!-- /Chat -->
    </flux:main>
</x-patxiai-patxi::layouts.app.header>
