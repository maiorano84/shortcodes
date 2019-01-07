<?php

namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;

/**
 * Interface ShortcodeManagerInterface
 * @package Maiorano\Shortcodes\Manager
 */
interface ManagerInterface
{
    /**
     * @param ShortcodeInterface $shortcode
     * @param string|null $name
     * @return ManagerInterface
     */
    public function register(ShortcodeInterface $shortcode, ?string $name = null): ManagerInterface;

    /**
     * @param string $shortcode
     * @return ManagerInterface
     */
    public function deregister(string $shortcode): ManagerInterface;

    /**
     * @param string $name
     * @return bool
     */
    public function isRegistered(string $name): bool;

    /**
     * @return array
     */
    public function getRegistered(): array;

    /**
     * @param string $content
     * @param array|string $tags
     * @return bool
     */
    public function hasShortcode(string $content, $tags = []): bool;

    /**
     * @param string $content
     * @param array|string $tags
     * @param bool $deep
     * @return string
     */
    public function doShortcode(string $content, $tags = [], bool $deep = false): string;
}
