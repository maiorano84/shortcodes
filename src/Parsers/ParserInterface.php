<?php
namespace Maiorano\Shortcodes\Parsers;

/**
 * Interface ParserInterface
 * @package Maiorano\Shortcodes\Parsers
 */
interface ParserInterface
{

    /**
     * @param $content
     * @return mixed
     */
    public function parseShortcode($content);
}