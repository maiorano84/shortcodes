<?php

namespace Maiorano\Shortcodes\Test;

use PHPUnit\Framework\TestCase;
use Maiorano\Shortcodes\Parsers\DefaultParser;

/**
 * Class ParserTest
 * @package Maiorano\Shortcodes\Test
 */
class ParserTest extends TestCase
{
    /**
     * @var DefaultParser
     */
    private $parser;

    /**
     *
     */
    public function setUp()
    {
        $this->parser = new DefaultParser;
    }

    /**
     *
     */
    public function testParseShortcodeContent()
    {
        $callback = function ($tag, $content, $atts) {
            return $content;
        };
        $content = $this->parser->parseShortcode('[tag]Content[/tag]', ['tag'], $callback);
        $empty = $this->parser->parseShortcode('[tag]', ['tag'], $callback);
        $this->assertEquals($content, 'Content');
        $this->assertEmpty($empty);
    }

    /**
     *
     */
    public function testParseShortcodeAttributes()
    {
        $simple = $this->parser->parseAttributes('attribute=value');
        $complex = $this->parser->parseAttributes('attribute="value1 value2"');
        $nameless = $this->parser->parseAttributes('attribute');

        $this->assertEquals($simple['attribute'], 'value');
        $this->assertEquals($complex['attribute'], 'value1 value2');
        $this->assertEquals($nameless[0], 'attribute');
    }
}