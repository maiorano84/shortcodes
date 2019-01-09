<?php

namespace Maiorano\Shortcodes\Contracts;

/**
 * Interface AttributeInterface
 * @package Maiorano\Shortcodes\Contracts
 */
interface AttributeInterface extends ShortcodeInterface
{
    /**
     * Get an array of recognized attributes and their defaults
     * @return array
     */
    public function getAttributes(): array;

    /**
     * Executed upon match and determines output of Shortcodes
     * @param string|null $content
     * @param array $atts
     * @return string
     */
    public function handle(?string $content = null, array $atts = []): string;
}
