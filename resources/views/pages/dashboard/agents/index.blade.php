<x-patxiai-patxi::layouts.app :title="__('Agents')">
    <div class="mx-auto w-full max-w-6xl">
        <section id="header">
            <flux:heading size="xl">{{__('Agents')}}</flux:heading>
            <flux:text class="mt-2 text-base">{{__('Install Agents to get new skills. Each department is led by an agent that delegates to its own sub-agents.')}}</flux:text>
        </section>

        <!-- Patxi -->
        <section id="agent-patxi" class="mt-6 mb-10">
            <flux:card class="flex items-center gap-5 p-5 bg-orange-50 border-orange-500! rounded-2xl shadow-none">
                <div class="w-16 h-8">
                    <x-patxiai-patxi::app-logo-icon />
                </div>

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

        <!-- Agents -->
        <section id="agents">
            @php
                $agents = app(\PatxiAI\PatxiCore\Repositories\AgentRepository::class)->all();
            @endphp

            @foreach ($agents as $agent)
                <!-- {{$agent['title']}} -->
                <section class="agents-{{$agent['title']}}">
                    <flux:heading size="lg" class="mb-2 text-zinc-600">{{$agent['title']}} {{__('Teams')}}</flux:heading>

                    <div class="grid grid-cols-2 gap-8">
                        @foreach ($agent['agents'] as $department)
                            @php
                                $isInstalled = \Composer\InstalledVersions::isInstalled($department['package']);
                            @endphp
                            <flux:card class="p-6 bg-white border border-zinc-200! rounded-2xl max-w-xl shadow-sm">
                                <div class="flex items-start justify-between w-full">
                                    <div class="flex items-center gap-4">
                                        <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 bg-orange-50 rounded-2xl">
                                            <flux:icon icon="{{$department['icon']}}" class="size-6 text-orange-700"/>
                                        </div>

                                        <div>
                                            <flux:heading size="lg" class="text-zinc-900! font-bold text-lg leading-tight">
                                                {{$department['name']}}
                                            </flux:heading>
                                            <flux:text variant="subtle" class="mt-0.5">
                                                {{__('Lead agent')}} · {{count($department['sub-agents'])}} {{__('sub-agents')}}
                                            </flux:text>
                                        </div>
                                    </div>

                                    @if ($isInstalled)
                                        <flux:badge size="sm" class="bg-green-50! text-green-700! border-none! px-2.5 py-0.5 rounded-full text-xs font-semibold flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                            {{__('Installed')}}
                                        </flux:badge>
                                    @endif
                                </div>

                                <flux:text class="text-zinc-600! text-sm mt-4 block leading-relaxed font-normal">
                                    {{$department['description']}}
                                </flux:text>

                                <div class="mt-3">
                                    <div class="text-zinc-400 text-[10px] font-bold tracking-wider uppercase mb-2">
                                        {{__('Includes')}}
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($department['sub-agents'] as $subagent)
                                            <flux:badge size="sm" class="bg-zinc-50! text-zinc-700! border-zinc-200! px-2.5 py-1 rounded-full text-xs font-medium flex items-center gap-1.5 shadow-none">
                                                <span class="w-1.5 h-1.5 {{$isInstalled ? 'bg-orange-500' : 'bg-zinc-500' }} rounded-full"></span>
                                                {{$subagent}}
                                            </flux:badge>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex gap-2.5 mt-4">
                                    @if ($isInstalled)
                                        <flux:button class="bg-orange-600! hover:bg-orange-700! text-white! font-semibold px-4 py-2 text-sm rounded-xl border-none! shadow-none transition-colors">
                                            {{__('Manage')}}
                                        </flux:button>

                                        <flux:button variant="outline" class="bg-white! border-zinc-200! text-zinc-700! font-semibold px-4 py-2 text-sm rounded-xl shadow-none hover:bg-zinc-50!">
                                            {{__('Uninstall')}}
                                        </flux:button>
                                    @else
                                        <flux:button class="bg-orange-600! hover:bg-orange-700! text-white! font-semibold px-4 py-2 text-sm rounded-xl border-none! shadow-none transition-colors">
                                            {{__('Install')}}
                                        </flux:button>
                                    @endif
                                </div>
                            </flux:card>
                        @endforeach
                    </div>
                </section>
                <!-- /{{$agent['title']}} -->
            @endforeach
        </section>
        <!-- /Agents -->

    </div>
</x-patxiai-patxi::layouts.app>

