<?php

namespace Codedor\FilamentPlaceholderInput\Providers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentPlaceholderInputServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-placeholder-input')
            ->hasViews()
            ->setBasePath(__DIR__ . '/../');
    }
}
