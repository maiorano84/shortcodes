<?php
namespace Maiorano\WPShortcodes\Shortcode;

/**
 * Interface ShortcodeInterface
 * @package Maiorano\WPShortcodes\Shortcode
 */
interface ShortcodeInterface{

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function handle();
}