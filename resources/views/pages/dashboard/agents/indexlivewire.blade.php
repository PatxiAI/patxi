<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Livewire\Component;

new #[Title__('Agents')] class extends Component
{
    public string $packageToInstall = '';

    public string $packageNameToInstall = '';

    public string $packageToUninstall = '';

    public string $packageNameToUninstall = '';

    public function confirmInstall(string $package, string $name): void
    {
        $this->packageToInstall = $package;
        $this->packageNameToInstall = $name;
        $this->modal('confirm-install')->show();
    }

    public function install(): void
    {
        $composer = $this->resolveComposerBinary();

        $result = Process::path(base_path())
            ->timeout(300)
            ->env(['PATH' => dirname($composer).':'.($_SERVER['PATH'] ?? '/usr/bin:/bin')])
            ->run([$composer, 'require', $this->packageToInstall]);

        if ($result->successful()) {
            $this->modal('confirm-install')->close();
            $this->redirectRoute('agents', navigate: true);
            Flux::toast(
                text: __('Agent installed successfully.'),
                variant: 'success',
            );
        } else {
            Log::error('Agent install attempt', [
                'package' => $this->packageToInstall,
                'composer' => $composer,
                'base_path' => base_path(),
                'exit_code' => $result->exitCode(),
                'output' => $result->output(),
                'error' => $result->errorOutput(),
            ]);
            Flux::toast(
                text: __('Failed to install :name. Check server logs for details.', ['name' => $this->packageNameToInstall]),
                variant: 'danger',
            );
        }
    }

    public function confirmUninstall(string $package, string $name): void
    {
        $this->packageToUninstall = $package;
        $this->packageNameToUninstall = $name;
        $this->modal('confirm-uninstall')->show();
    }

    public function uninstall(): void
    {
        $composer = $this->resolveComposerBinary();

        $result = Process::path(base_path())
            ->timeout(120)
            ->env(['PATH' => dirname($composer).':'.($_SERVER['PATH'] ?? '/usr/bin:/bin')])
            ->run([$composer, 'remove', $this->packageToUninstall]);

        if ($result->successful()) {
            $this->modal('confirm-uninstall')->close();
            $this->redirectRoute('agents', navigate: true);
            Flux::toast(
                text: __('Agent removed successfully.'),
                variant: 'success',
            );
        } else {
            Log::info('Agent uninstall attempt', [
                'package' => $this->packageToUninstall,
                'composer' => $composer,
                'base_path' => base_path(),
                'exit_code' => $result->exitCode(),
                'output' => $result->output(),
                'error' => $result->errorOutput(),
            ]);
            Flux::toast(
                text: __('Failed to uninstall :name. Check server logs for details.', ['name' => $this->packageNameToUninstall]),
                variant: 'danger',
            );
        }
    }

    private function resolveComposerBinary(): string
    {
        $candidates = [
            $_SERVER['HOME'].'/Library/Application Support/Herd/bin/composer',
            '/usr/local/bin/composer',
            '/usr/bin/composer',
        ];

        foreach ($candidates as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        return 'composer';
    }
}
?>

<div class="mx-auto w-full max-w-6xl">
    <section id="header">
        <flux:heading size="xl">{{__('Agents')}}</flux:heading>
        <flux:text class="mt-2 text-base">{{__('Install Agents to get new skills. Each department is led by an agent that delegates to its own sub-agents.')}}</flux:text>
    </section>

    <!-- Patxi -->
    <section id="agent-patxi" class="mt-6 mb-10">
        <flux:card class="flex items-center gap-5 p-5 bg-orange-50 border-orange-500! rounded-2xl shadow-none">
            <div class="w-16">
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

                                    <flux:button wire:click="confirmUninstall('{{ $department['package'] }}', '{{ $department['name'] }}')" variant="outline" class="bg-white! border-zinc-200! text-zinc-700! font-semibold px-4 py-2 text-sm rounded-xl shadow-none hover:bg-zinc-50!">
                                        {{__('Uninstall')}}
                                    </flux:button>
                                @else
                                    <flux:button wire:click="confirmInstall('{{ $department['package'] }}', '{{ $department['name'] }}')" class="bg-orange-600! hover:bg-orange-700! text-white! font-semibold px-4 py-2 text-sm rounded-xl border-none! shadow-none transition-colors">
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

    <flux:modal name="confirm-install" class="max-w-sm w-full">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">{{__('Install agent')}}</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-500">
                    {{__('Install :name? This will download and enable the package and all its capabilities.', ['name' => $packageNameToInstall])}}
                </flux:text>
            </div>

            <div class="flex justify-end gap-2">
                <flux:button x-on:click="$flux.modal('confirm-install').close()" variant="ghost">
                    {{__('Cancel')}}
                </flux:button>

                <flux:button wire:click="install()" wire:loading.attr="disabled" class="bg-orange-600! hover:bg-orange-700! text-white! border-none!">
                    <span wire:loading.remove wire:target="install">{{__('Install')}}</span>
                    <span wire:loading wire:target="install">{{__('Installing...')}}</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="confirm-uninstall" class="max-w-sm w-full">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">{{__('Uninstall agent')}}</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-500">
                    {{__('Are you sure you want to uninstall :name? This will remove the package and all its capabilities.', ['name' => $packageNameToUninstall])}}
                </flux:text>
            </div>

            <div class="flex justify-end gap-2">
                <flux:button x-on:click="$flux.modal('confirm-uninstall').close()" variant="ghost">
                    {{__('Cancel')}}
                </flux:button>

                <flux:button wire:click="uninstall()" wire:loading.attr="disabled" variant="danger">
                    <span wire:loading.remove wire:target="uninstall">{{__('Uninstall')}}</span>
                    <span wire:loading wire:target="uninstall">{{__('Uninstalling...')}}</span>
                </flux:button>
            </div>
        </div>
    </flux:modal>

</div>
