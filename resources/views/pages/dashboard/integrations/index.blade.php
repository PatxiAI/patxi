<x-patxiai-patxi::layouts.app :title="__('Integrations')">
    <div class="mx-auto w-full max-w-6xl">
        <section id="header" class="mb-8">
            <flux:heading size="xl">{{__('Integrations')}}</flux:heading>
            <flux:text class="mt-2 text-base">{{__('Connect PatxiAI to the tools you already use. After that, Patxi can then read, act and stay in sync across them.')}}</flux:text>
        </section>


        <!-- Integrations -->
        <section id="integrations" class="space-y-8">
            @php
                $integrations = app(\PatxiAI\PatxiCore\Repositories\IntegrationRepository::class)->all();
            @endphp

            @foreach ($integrations as $key => $integration)
                <flux:heading size="lg" class="mb-2 text-zinc-600">{{$key}}</flux:heading>

                <div class="grid grid-cols-3 gap-8">
                    @foreach ($integration as $tool)
                        @php
                            $isInstalled = \Composer\InstalledVersions::isInstalled($tool['package']);
                        @endphp
                        <flux:card class="flex justify-between items-center gap-4 {{$isInstalled ? 'border-orange-500!' : ''}}">
                            <div class="flex items-center gap-4">
                                <img src="{{$tool['logo']}}" class="size-12"/>
                                <div>
                                    <flux:heading size="lg" class="text-zinc-900! font-bold text-lg leading-tight">
                                        {{$tool['name']}}
                                    </flux:heading>
                                    <flux:text variant="subtle" class="mt-0.5">
                                        <flux:badge size="sm" class="bg-zinc-50! text-zinc-700! border-zinc-200! px-2.5 py-1 rounded-full text-xs font-medium flex items-center gap-1.5 shadow-none">
                                            <span class="w-1.5 h-1.5 {{$isInstalled ? 'bg-orange-500' : 'bg-zinc-500' }} rounded-full"></span>
                                            {{$isInstalled ? __('Connected') : __('Not connected')}}
                                        </flux:badge>
                                    </flux:text>
                                </div>
                            </div>

                            <div>
                                @if ($isInstalled)
                                    <flux:button variant="outline" size="sm" class="bg-white! border-zinc-200! text-zinc-700! font-medium px-4 py-2 text-sm rounded-md shadow-none hover:bg-zinc-50!">
                                        {{__('Manage')}}
                                    </flux:button>
                                @else
                                    <flux:button variant="outline" size="sm" class="bg-white! border-zinc-200! text-zinc-700! font-medium px-4 py-2 text-sm rounded-md shadow-none hover:bg-zinc-50!">
                                        {{__('Install')}}
                                    </flux:button>
                                @endif
                            </div>
                        </flux:card>
                    @endforeach
                </div>
            @endforeach
        </section>
        <!-- /Integrations -->
    </div>
</x-patxiai-patxi::layouts.app>

