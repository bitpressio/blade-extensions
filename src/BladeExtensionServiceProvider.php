<?php

namespace BitPress\BladeExtension;

use Illuminate\Support\ServiceProvider;
use BitPress\BladeExtension\Contracts\BladeExtension;
use BitPress\BladeExtension\Exceptions\InvalidBladeExtension;

class BladeExtensionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->defineBladeExtensions();
    }

    public function register()
    {
    }

    /**
     * Registers Blade Extension directives and conditionals with the blade compiler
     *
     * @return null
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
