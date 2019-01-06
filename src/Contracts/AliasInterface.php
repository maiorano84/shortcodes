<?php

namespace Maiorano\Shortcodes\Contracts;

use Maiorano\Shortcodes\Exceptions\RegisterException;

/**
 * Interface AliasInterface
 * @package Maiorano\Shortcodes\Contracts
 */
interface AliasInterface
{
    /**
     * Store the alias, and register through the Manager if available
     * @param string $alias
     * @return AliasInterface
     * @throws RegisterException
     */
    public function alias(string $alias): AliasInterface;

    /**
     * Returns an array of registered aliases
     * @return array
     */
    public function getAlias(): array;
}
