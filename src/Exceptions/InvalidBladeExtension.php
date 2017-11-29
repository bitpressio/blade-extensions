<?php

namespace BitPress\BladeExtension\Exceptions;

use BitPress\BladeExtension\Contracts\BladeExtension;

class InvalidBladeExtension extends BladeExtensionException
{
    public function __construct($extension)
    {
        parent::__construct(sprintf(
            "\"%s\" is an invalid Blade extension.\n\nBlade extensions must implement the \"%s\" contract.",
            get_class($extension),
            BladeExtension::class
        ));
    }
}
