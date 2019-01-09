<?php

namespace Maiorano\Shortcodes\Test;

use Maiorano\Shortcodes\Parsers\DefaultParser;
use PHPUnit\Framework\TestCase;

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
     * @return void
     */
    public function setUp(): void
    {
        $this->parser = new DefaultParser;
    }

    /**
     * @return void
     */
    public function testParseShortcodeNoCallback(): void
    {
        $empty = $this->parser->parseShortcode('String without shortcode', []);
        $escaped = $this->parser->parseShortcode('[[foo]]', ['foo']);
        $content = $this->parser->parseShortcode('[foo]Bar[/foo]', ['foo']);

        $this->assertEmpty($empty);
        $this->assertEmpty($escaped);
        $this->assertEmpty($content[0]['attributes']);
        $this->assertEquals('foo', $content[0]['tag']);
        $this->assertEquals('Bar', $content[0]['content']);
    }

    /**
     * @return void
     */
    public function testParseShortcodeCallback(): void
    {
        $callback = function ($tag, $content, $attributes) {
            return $content;
        };

        $empty = $this->parser->parseShortcode('String without shortcode', [], $callback);
        $escaped = $this->parser->parseShortcode('[[foo]]', ['foo'], $callback);
        $content = $this->parser->parseShortcode('[foo]Bar[/foo]', ['foo'], $callback);

        $this->assertEquals('String without shortcode', $empty);
        $this->assertEquals('[foo]', $escaped);
        $this->assertEquals('Bar', $content);
    }

    /**
     * @param string $text
     * @param string $key
     * @param string|bool $expected
     * @param int $count
     * @return void
     * @dataProvider attributeVariationProvider
     */
    public function testParseShortcodeAttributes(string $text, string $key, $expected, int $count): void
    {
        $parsed = $this->parser->parseAttributes($text);

        $this->assertCount($count, $parsed);
        $this->assertEquals($parsed[$key], $expected);
    }

    /**
     * @return array
     */
    public function attributeVariationProvider(): array
    {
        return [
            ['attribute="value"', 'attribute', 'value', 1],
            ['attribute=\'value\'', 'attribute', 'value', 1],
            ['attribute=value', 'attribute', 'value', 1],
            ['"attribute"', 'attribute', true, 1],
            ['\'attribute\'', 'attribute', true, 1],
            ['attribute', 'attribute', true, 1],
            ['attribute="<div"', 'attribute', '', 1]
        ];
    }
}