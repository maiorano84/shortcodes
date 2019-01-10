<?php

namespace Maiorano\Shortcodes\Parsers;

use Closure;

/**
 * Interface ParserInterface.
 */
interface ParserInterface
{
    /**
     * Scan all content for possible shortcode.
     *
     * @param string       $content
     * @param array        $tags
     * @param Closure|null $callback
     *
     * @return mixed
     */
    public function parseShortcode(string $content, array $tags, Closure $callback = null);
}
