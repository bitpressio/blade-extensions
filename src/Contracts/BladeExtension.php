<?php

namespace BitPress\BladeExtension\Contracts;

interface BladeExtension
{
    /**
     * Get custom directives
     *
     * ```php
     * return [
     *     'truncate' => [$this, 'truncate']
     * ];
     * ```
     *
     * @return array
     */
    public function getDirectives();

    /**
     * Get custom conditional directives
     *
     * ```php
     * return [
     *     'prod' => [$this, 'isProd']
     * ];
     * ```
     *
     * @return array
     */
    public function getConditionals();
}
