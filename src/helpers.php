<?php

use BitPress\BladeExtension\Container\BladeRegistrar;

if (! function_exists('blade_extension')) {
    /**
     * @param  string|array  $abstract
     * @param  \Closure|string|null  $concrete
     */
    function blade_extension($service, $concrete = null)
    {
        BladeRegistrar::register($service, $concrete);
    }
}
