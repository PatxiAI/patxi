<x-patxiai-patxi::layouts.app.header :title="__('Agent Patxi')">
    <flux:main class="p-0! flex h-full">
        <flux:sidebar sticky collapsible="mobile" class="w-84 h-full bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:button icon="plus" variant="primary" color="orange" class="w-full">
                    {{__('New chat')}}
                </flux:button>
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>
            <flux:input icon="magnifying-glass" placeholder="Search chats" class="[&>input]:bg-zinc-50! [&>input]:h-9! [&>input]:focus:outline-none! [&>input]:focus:ring-0!" />
            {{-- <flux:sidebar.search placeholder="Search chats" class="bg-zinc-50! border! border-zinc-200!" /> --}}
        </flux:sidebar>

        <!-- Chat -->
        <section id="chat" class="w-full h-full flex flex-col bg-orange-50 dark:bg-zinc-900">
            <!-- Header -->
            <div class="p-4 flex justify-between gap-x-4 border-b border-zinc-200 dark:border-zinc-700 shrink-0">
                <flux:text variant="strong" class="font-bold">Title of the chat</flux:text>
            </div>
            <!-- /Header -->

            <div class="p-6 mx-auto w-full max-w-3xl flex flex-col flex-1 min-h-0">
                <!-- Messages -->
                <div class="grow overflow-y-auto min-h-0">
                    el chat
                </div>
                <!-- /Messages -->

                <!-- Input Area -->
                <div class="shrink-0">
                    <flux:card class="mb-2 p-2">
                        <flux:textarea rows="auto" resize="none" class="mb-2 border-0 shadow-none! focus:outline-none focus:ring-0" placeholder="{{__('Patxi is ready for you...')}}" />
                        <div class="flex justify-between items-center">
                            <div class="flex gap-2">
                                <flux:icon.paper-clip class="size-5 text-gray-500"/>
                                <flux:icon.globe-alt class="size-5 text-gray-500"/>
                            </div>

                            <flux:button icon="arrow-up" variant="primary" color="orange" />
                        </div>
                    </flux:card>
                    <flux:text class="text-center text-xs">{{__('AI can make mistakes. Verify important information')}}</flux:text>
                </div>
                <!-- /Input Area -->
            </div>
        </section>
        <!-- /Chat -->
    </flux:main>
</x-patxiai-patxi::layouts.app.header>





