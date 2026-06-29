<x-patxiai-patxi::layouts.app :title="__('Agents')">
    <div class="mx-auto w-full max-w-6xl">
        <section id="header">
            <flux:heading size="xl">{{__('Agents')}}</flux:heading>
            <flux:text class="mt-2 text-base">{{__('Install Agents to get new skills. Each department is led by an agent that delegates to its own sub-agents.')}}</flux:text>
        </section>

        <!-- Patxi -->
        <section id="agent-patxi" class="mt-6 mb-10">
            <flux:card class="flex items-center gap-5 p-5 bg-orange-50 border-orange-500! rounded-2xl shadow-none">
                <x-patxiai-patxi::app-logo-icon />

                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <flux:heading size="lg" class="text-zinc-900! font-bold text-base tracking-tight">
                            {{__('Patxi Assistant')}}
                        </flux:heading>

                        <flux:badge size="sm" class="bg-[#fff0e4]! text-[#c65416]! border-[#fcdcc5]! px-2.5 py-0.5 rounded-full text-xs font-semibold">
                            {{__('Main agent')}}
                        </flux:badge>
                    </div>

                    <flux:text class="text-zinc-600! text-sm leading-relaxed max-w-2xl">
                        {{__('The agent you talt to in chat. It understands what you need and routes the work to the right department and sub-agents – you never manage them directly. It\'s always on and can\'t be unninstalled.')}}
                    </flux:text>
                </div>

                <div class="flex-shrink-0 pl-4">
                    <flux:badge size="sm" class="bg-[#edfbf0]! text-[#15803d]! border-none! px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 bg-[#22c55e] rounded-full"></span>
                        {{__('Always on')}}
                    </flux:badge>
                </div>
            </flux:card>
        </section>
        <!-- /Patxi -->

        <!-- Work -->
        <section class="agents-work">
            <flux:heading size="lg" class="mb-2 text-zinc-600">{{__('Work Departments')}}</flux:heading>

            <!-- Work Departments -->
            <div class="grid grid-cols-2 gap-4">
                <flux:card class="p-6 bg-white border border-zinc-200! rounded-2xl max-w-xl shadow-sm">
                    <div class="flex items-start justify-between w-full">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-orange-50 rounded-2xl">
                                <svg class="w-6 h-6 text-orange-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                </svg>
                            </div>

                            <div>
                                <flux:heading size="lg" class="text-zinc-900! font-bold text-lg leading-tight">
                                    {{__('Marketing')}}
                                </flux:heading>
                                <flux:text variant="subtle" class="mt-0.5">
                                    {{__('Lead agent')}} · 3 {{__('sub-agents')}}
                                </flux:text>
                            </div>
                        </div>

                        <flux:badge size="sm" class="bg-green-50! text-green-700! border-none! px-2.5 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                            {{__('Installed')}}
                        </flux:badge>
                    </div>

                    <flux:text class="text-zinc-600! text-sm mt-4 block leading-relaxed font-normal">
                        {{__('Plans campaigns, grows your audience and runs paid acquisition across channels.')}}
                    </flux:text>

                    <div class="mt-3">
                        <div class="text-zinc-400 text-[10px] font-bold tracking-wider uppercase mb-2">
                            {{__('Includes')}}
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <flux:badge size="sm" class="bg-zinc-50! text-zinc-700! border-zinc-200! px-2.5 py-1 rounded-full text-xs font-medium flex items-center gap-1.5 shadow-none">
                                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>
                                {{__('CommunityManager')}}
                            </flux:badge>

                            <flux:badge size="sm" class="bg-zinc-50! text-zinc-700! border-zinc-200! px-2.5 py-1 rounded-full text-xs font-medium flex items-center gap-1.5 shadow-none">
                                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>
                                {{__('SEOExpert')}}
                            </flux:badge>

                            <flux:badge size="sm" class="bg-zinc-50! text-zinc-700! border-zinc-200! px-2.5 py-1 rounded-full text-xs font-medium flex items-center gap-1.5 shadow-none">
                                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>
                                {{__('SocialAds')}}
                            </flux:badge>
                        </div>
                    </div>

                    <div class="flex gap-2.5 mt-4">
                        <flux:button class="bg-orange-600! hover:bg-orange-700! text-white! font-semibold px-4 py-2 text-sm rounded-xl border-none! shadow-none transition-colors">
                            {{__('Manage')}}
                        </flux:button>

                        <flux:button variant="outline" class="bg-white! border-zinc-200! text-zinc-700! font-semibold px-4 py-2 text-sm rounded-xl shadow-none hover:bg-zinc-50!">
                            {{__('Uninstall')}}
                        </flux:button>
                    </div>
                </flux:card>
            </div>
            <!-- /Work Departments -->
        </section>
        <!-- /Work -->
    </div>
</x-patxiai-patxi::layouts.app>

