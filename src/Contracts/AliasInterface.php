<?php
namespace Maiorano\Shortcodes\Contracts;

/**
 * Interface AliasInterface
 * @package Maiorano\Shortcodes\Contracts
 */
interface AliasInterface
{

    /**
     * Store the alias, and register through the Manager if available
     * @param string $alias
     * @return ShortcodeInterface
     * @throws \Maiorano\Shortcodes\Exceptions\RegisterException
     */
    public function alias($alias);

    /**
     * Returns an array of registered aliases
     * @return array
     */
    public function getAlias();
}
