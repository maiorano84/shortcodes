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
     * @return mixed
     */
    public function register(ShortcodeInterface $shortcode);

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
     * @param array $tags
     * @return bool
     */
    public function hasShortcode($content, $tags = []);

    /**
     * @param string $content
     * @param array $tags
     * @return string
     */
    public function doShortcode($content, $tags = []);

    /**
     * @return  \Maiorano\Shortcodes\Parsers\ParserInterface
     */
    public function getParser();
}
