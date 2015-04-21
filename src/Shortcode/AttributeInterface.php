<?php
namespace Maiorano\WPShortcodes\Shortcode;

/**
 * Interface AttributeInterface
 * @package Maiorano\WPShortcodes\Shortcode
 */
interface AttributeInterface
{

    /**
     * Parse a given string into key=>value pairs
     * @param string $text
     * @return array
     */
    public function parseAttributes($text);

    /**
     * Called by the Shortcode handler
     * Usually used to merge the results of parseAttributes() with an optionally provided array of defaults
     * @return array
     */
    public function getAttributes();
}