<?php

namespace BitPress\BladeExtension\Container;

class BladeRegistrar
{
    /**
     * Register and tag a blade service in the container
     *
     * @param  string|array  $abstract
     * @param  \Closure|string|null  $concrete
     */
    public static function register($extension, $concrete = null)
    {
        app()->singleton($extension, $concrete);
        app()->tag($extension, 'blade.extension');
    }
}
