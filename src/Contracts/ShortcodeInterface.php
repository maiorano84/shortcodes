<?php

namespace Maiorano\Shortcodes\Contracts;

/**
 * Interface ShortcodeInterface
 * @package Maiorano\Shortcodes\Contracts
 */
interface ShortcodeInterface
{
    /**
     * All Shortcodes must return a name as a string
     * This is used by the management container for registration
     * @return string
     */
    public function getName(): string;

    /**
     * Executed upon match and determines output of Shortcodes
     * @param string|null $content
     * @param array $atts
     * @return string
     */
    public function handle(?string $content = null, array $atts = []): string;
}
