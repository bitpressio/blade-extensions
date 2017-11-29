<?php

namespace BitPress\BladeExtension\Contracts;

interface BladeExtension
{
    /**
     * Get custom directives this extension defines
     * @return array
     */
    public function getDirectives();

    /**
     * Get custom conditional directives
     * @return array
     */
    public function getConditionals();
}
