<?php

namespace Maiorano\Shortcodes\Contracts;

/**
 * Interface AliasInterface.
 */
interface AliasInterface extends ShortcodeInterface
{
    /**
     * Store the alias, and register through the Manager if available.
     *
     * @param string $alias
     *
     * @return static
     */
    public function alias(string $alias): self;

    /**
     * Returns an array of registered aliases.
     *
     * @return array
     */
    public function getAlias(): array;
}
