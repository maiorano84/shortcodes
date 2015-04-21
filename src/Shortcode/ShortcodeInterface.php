<?php
namespace Maiorano\WPShortcodes\Shortcode;

/**
 * Interface ShortcodeInterface
 * @package Maiorano\WPShortcodes\Shortcode
 */
interface ShortcodeInterface{

    /**
     * All Shortcodes must return a name as a string
     * This is used by the management container for registration
     * @return string
     */
    public function getName();

    /**
     * Executed upon match and determines output of Shortcode
     * @return string
     */
    public function handle();
}