<?php
namespace Maiorano\Shortcodes\Test;

use Maiorano\Shortcodes\Parsers\WordpressParser;

class ParserTest extends TestCase
{
    public function testParseShortcodeContent()
    {
        $parser = new WordpressParser;
        $callback = function ($tag, $content, $atts) {
            return $content;
        };
        $content = $parser->parseShortcode('Content', array('tag'), $callback);
        $empty = $parser->parseShortcode('[tag]', array('tag'), $callback);
        $this->assertEquals($content, 'Content');
        $this->assertEmpty($empty);
    }

    public function testParseShortcodeAttributes()
    {
        $parser = new WordpressParser;
        $simple = $parser->parseAttributes('attribute=value');
        $complex = $parser->parseAttributes('attribute="value1 value2"');
        $nameless = $parser->parseAttributes('attribute');

        $this->assertEquals($simple['attribute'], 'value');
        $this->assertEquals($complex['attribute'], 'value1 value2');
        $this->assertEquals($nameless[0], 'attribute');
    }
}