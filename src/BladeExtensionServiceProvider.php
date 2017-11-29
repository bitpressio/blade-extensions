<?php

namespace BitPress\BladeExtension;

use Illuminate\Support\ServiceProvider;
use BitPress\BladeExtension\Contracts\BladeExtension;
use BitPress\BladeExtension\Exceptions\InvalidBladeExtension;

class BladeExtensionServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap package services
     *
     * @return void
     */
    public function boot()
    {
        $this->defineBladeExtensions();
        if ($this->app->runningInConsole()) {
            $this->defineCommands();
        }
    }

    /**
     * Register package services
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Define package console commands
     */
    protected function defineCommands()
    {
        $this->commands([
            Console\Commands\BladeExtensionMakeCommand::class,
        ]);
    }

    /**
     * Register Blade Extension directives and conditionals with the blade compiler
     *
     * @return void
     */
    protected function defineBladeExtensions()
    {
        foreach ($this->app->tagged('blade.extension') as $extension) {
            if (! $extension instanceof BladeExtension) {
                throw new InvalidBladeExtension($extension);
            }

            foreach ($extension->getDirectives() as $name => $callable) {
                $this->app['blade.compiler']->directive($name, $callable);
            }

            foreach ($extension->getConditionals() as $name => $callable) {
                $this->app['blade.compiler']->if($name, $callable);
            }
        }
    }
}
