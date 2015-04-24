<?php
namespace Maiorano\Shortcodes\Contracts;

/**
 * Interface AliasInterface
 * @package Maiorano\Shortcodes\Contracts
 */
interface AliasInterface
{

    /**
     * Convenience method
     * Store the alias, and register through the Manager
     * @param string $alias
     * @return ShortcodeInterface
     * @throws \Maiorano\Shortcodes\Exceptions\RegisterException
     */
    public function alias($alias);

    /**
     * @return array
     */
    public function getAlias();
}