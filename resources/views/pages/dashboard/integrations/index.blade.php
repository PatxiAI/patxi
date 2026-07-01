<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Livewire\Component;

new #[Title__('Integrations')] class extends Component
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
            $this->redirectRoute('integrations', navigate: true);
            Flux::toast(
                text: __('Integration installed successfully.'),
                variant: 'success',
            );
        } else {
            Log::error('Integration install attempt', [
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
            $this->redirectRoute('integrations', navigate: true);
            Flux::toast(
                text: __('Integration removed successfully.'),
                variant: 'success',
            );
        } else {
            Log::info('Integration uninstall attempt', [
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

            <div class="grid grid-cols-4 gap-6">
                @foreach ($integration as $tool)
                    @php
                        $isInstalled = \Composer\InstalledVersions::isInstalled($tool['package']);
                    @endphp
                    <flux:card class="flex flex-col justify-between gap-4 {{$isInstalled ? 'border-orange-500!' : ''}}">
                        <div class="flex items-center gap-3">
                            <img src="{{$tool['logo']}}" class="size-10 flex-shrink-0"/>
                            <div class="min-w-0">
                                <flux:heading size="lg" class="text-zinc-900! font-bold text-base leading-tight truncate">
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

                        <div class="flex gap-2">
                            @if ($isInstalled)
                                <flux:button variant="outline" size="sm" class="flex-1 bg-white! border-zinc-200! text-zinc-700! font-medium px-2 py-1 text-xs rounded-md shadow-none hover:bg-zinc-50!">
                                    {{__('Manage')}}
                                </flux:button>

                                <flux:button wire:click="confirmUninstall('{{ $tool['package'] }}', '{{ $tool['name'] }}')" variant="outline" size="sm" class="flex-1 bg-white! border-zinc-200! text-zinc-700! font-medium px-2 py-1 text-xs rounded-md shadow-none hover:bg-zinc-50!">
                                    {{__('Uninstall')}}
                                </flux:button>
                            @else
                                <flux:button wire:click="confirmInstall('{{ $tool['package'] }}', '{{ $tool['name'] }}')" variant="outline" size="sm" class="w-full bg-white! border-zinc-200! text-zinc-700! font-medium px-2 py-1 text-xs rounded-md shadow-none hover:bg-zinc-50!">
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

    <flux:modal name="confirm-install" class="max-w-sm w-full">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg">{{__('Install integration')}}</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-500">
                    {{__('Install :name? This will download and enable the integration and all its capabilities.', ['name' => $packageNameToInstall])}}
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
                <flux:heading size="lg">{{__('Uninstall integration')}}</flux:heading>
                <flux:text class="mt-1 text-sm text-zinc-500">
                    {{__('Are you sure you want to uninstall :name? This will remove the integration and all its capabilities.', ['name' => $packageNameToUninstall])}}
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
