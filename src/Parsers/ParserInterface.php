<?php
namespace Maiorano\Shortcodes\Parsers;

/**
 * Interface ParserInterface
 * @package Maiorano\Shortcodes\Parsers
 */
interface ParserInterface
{

    /**
     * Scan all content for possible shortcode
     * @param array $match
     * @return mixed
     */
    public function parseShortcode($match);
}
