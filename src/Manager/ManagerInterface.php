<?php

namespace Maiorano\Shortcodes\Manager;

use Maiorano\Shortcodes\Contracts\ShortcodeInterface;

/**
 * Interface ShortcodeManagerInterface
 * @package Maiorano\Shortcodes\Manager
 */
interface ManagerInterface
{
    public function registerAll(array $shortcodes);

    /**
     * @param ShortcodeInterface $shortcode
     * @param string|null $name
     * @return ManagerInterface
     */
    public function register(ShortcodeInterface $shortcode, ?string $name = null): ManagerInterface;

    /**
     * @param string $shortcode
     * @return mixed
     */
    public function deregister($shortcode);

    /**
     * @param string $name
     * @return bool
     */
    public function isRegistered($name);

    /**
     * @return mixed
     */
    public function getRegistered();

    /**
     * @param string $name
     * @param string $alias
     * @return mixed
     */
    public function alias($name, $alias);

    /**
     * @param string $content
     * @param array|string $tags
     * @return bool
     */
    public function hasShortcode($content, $tags = []);

    /**
     * @param string $content
     * @param array|string $tags
     * @param bool $deep
     * @return string
     */
    public function doShortcode($content, $tags = [], $deep = false);

    /**
     * @return  \Maiorano\Shortcodes\Parsers\ParserInterface
     */
    public function getParser();
}
