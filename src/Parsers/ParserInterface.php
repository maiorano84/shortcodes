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
     * @param string $content
     * @param array $tags
     * @param Callable $callback
     * @return mixed
     */
    public function parseShortcode(string $content, array $tags, Callable $callback = null);
}
