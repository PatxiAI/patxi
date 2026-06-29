<?php

declare(strict_types=1);

namespace PatxiAI\Patxi;

use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;
use Livewire\Livewire;
use Override;
use PatxiAI\Patxi\Console\Commands\InstallPatxiAI;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PatxiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('patxiai-patxi')
            ->hasCommand(InstallPatxiAI::class)
            ->hasRoutes('web')
            ->hasViews();
    }

    #[Override]
    public function packageRegistered()
    {
        $this->app->instance(LoginResponse::class, new class implements LoginResponse
        {
            public function toResponse($request)
            {
                return redirect()->route('dashboard');
            }
        });

        $this->app->instance(RegisterResponse::class, new class implements RegisterResponse
        {
            public function toResponse($request)
            {
                return redirect()->route('dashboard');
            }
        });
    }

    public function bootingPackage(): void
    {
        Livewire::addNamespace('patxiai-patxi', viewPath: __DIR__.'/../resources/views');

        app('view')->addNamespace('layouts', __DIR__.'/../resources/views/components/layouts');

        Fortify::loginView(fn () => view('patxiai-patxi::pages.auth.login'));
        Fortify::registerView(fn () => view('patxiai-patxi::pages.auth.register'));
        Fortify::requestPasswordResetLinkView(fn () => view('patxiai-patxi::pages.auth.forgot-password'));
        Fortify::confirmPasswordView(fn () => view('patxiai-patxi::pages.auth.confirm-password'));
    }
}
