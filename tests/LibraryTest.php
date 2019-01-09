<?php

namespace Maiorano\Shortcodes\Test;

use Maiorano\Shortcodes\Contracts;
use Maiorano\Shortcodes\Library;
use PHPUnit\Framework\TestCase;
use Exception;

/**
 * Class LibraryTest
 * @package Maiorano\Shortcodes\Test
 */
class LibraryTest extends TestCase
{
    /**
     * @var Library\Age
     */
    private $age;
    /**
     * @var Library\Ipsum
     */
    private $ipsum;
    /**
     * @var Library\SimpleShortcode
     */
    private $simple;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->age = new Library\Age;
        $this->ipsum = new Library\Ipsum;
        $this->simple = new Library\SimpleShortcode('simple', [], function (?string $content = null, array $atts = []) {
            return 'simple';
        });
    }

    /**
     * @param string $unit
     * @param string $time
     * @throws Exception
     * @return void
     * @dataProvider ageVariationProvider
     */
    public function testAgeShortcode(string $unit, string $time): void
    {
        $date = strtotime($time);
        $result = $this->age->handle("@{$date}", ['units' => $unit]);
        $this->assertEquals("1 {$unit}", $result);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testAgeShortcodeEmpty(): void
    {
        $this->assertEquals('', $this->age->handle());
    }

    /**
     * @return void
     */
    public function testIpsumShortcode(): void
    {
        $str = trim((string)preg_replace('/\s+/', ' ', '
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent laoreet eu nulla sit amet porttitor. Sed 
        accumsan nulla est, sit amet lobortis nunc convallis pretium. Phasellus aliquet euismod lacus, non maximus 
        odio pulvinar quis. Nulla eu lorem malesuada, aliquam risus sit amet, interdum ligula. Vivamus sollicitudin 
        bibendum accumsan. Maecenas sit amet ornare turpis, quis porttitor quam. Mauris turpis purus, bibendum in diam 
        in, rutrum accumsan arcu.
        '));
        $this->assertSame($str, $this->ipsum->handle());
    }

    /**
     * @return void
     */
    public function testSimpleShortcode(): void
    {
        $noContent = new Library\SimpleShortcode('alt');
        $this->assertInstanceOf(Contracts\AliasInterface::class, $this->simple);
        $this->assertInstanceOf(Contracts\AttributeInterface::class, $this->simple);
        $this->assertInstanceOf(Contracts\ContainerAwareInterface::class, $this->simple);
        $this->assertInstanceOf(Contracts\ShortcodeInterface::class, $this->simple);
        $this->assertEquals('simple', $this->simple->handle('test'));
        $this->assertEquals('test', $noContent->handle('test'));
    }

    /**
     * @return array
     */
    public function ageVariationProvider(): array
    {
        return [
            ['centuries', '-100 years'],
            ['decades', '-10 years'],
            ['years', '-1 year'],
            ['months', '-1 month'],
            ['days', '-1 day'],
            ['hours', '-1 hour'],
            ['minutes', '-1 minute'],
            ['seconds', '-1 second'],
        ];
    }
}