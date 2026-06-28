<?php

declare(strict_types=1);

namespace PatxiAI\Patxi;

use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Str;
use Override;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PatxiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('patxiai-patxi')
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
        $userModel = config('auth.providers.users.model', \App\Models\User::class);
        $userModel::macro('initials', function (): string {
            $initials = Str::initials($this->name);

            return Str::length($initials) > 1
                ? Str::substr($initials, 0, 1) . Str::substr($initials, -1)
                : $initials;
        });

        Fortify::loginView(fn () => view('patxiai-patxi::pages.auth.login'));
        Fortify::registerView(fn () => view('patxiai-patxi::pages.auth.register'));
        Fortify::requestPasswordResetLinkView(fn () => view('patxiai-patxi::pages.auth.forgot-password'));
    }
}
