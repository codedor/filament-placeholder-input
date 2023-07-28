<?php

namespace Codedor\FilamentPlaceholderInput\Tests;

use Codedor\FilamentPlaceholderInput\Providers\FilamentPlaceholderInputServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Codedor\\FilamentPlaceholderInput\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            FilamentPlaceholderInputServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_filament-placeholder-input_table.php.stub';
        $migration->up();
        */
    }
}
