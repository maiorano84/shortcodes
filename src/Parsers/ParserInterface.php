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
     * @param $content
     * @return mixed
     */
    public function parseContent($content);


    /**
     * Parse the results of a single match
     * @param array $match
     * @return mixed
     */
    public function parseShortcode($match);
}