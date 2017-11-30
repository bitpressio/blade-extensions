<?php

namespace BitPress\BladeExtension\Contracts;

interface BladeExtension
{
    /**
     * Register directives
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
     * Register conditional directives
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
