<?php

namespace Maiorano\Shortcodes\Test\Unit;

use Exception;
use Maiorano\Shortcodes\Contracts;
use Maiorano\Shortcodes\Library;
use PHPUnit\Framework\TestCase;

/**
 * Class LibraryTest.
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
        $this->age = new Library\Age();
        $this->ipsum = new Library\Ipsum();
        $this->simple = new Library\SimpleShortcode('simple', [], function (?string $content = null, array $atts = []) {
            return 'simple';
        });
    }

    /**
     * @param string $unit
     *
     * @throws Exception
     *
     * @return void
     * @dataProvider ageVariationProvider
     */
    public function testAgeShortcode(string $unit): void
    {
        $result = $this->age->handle("now", ['units' => $unit]);
        $this->assertEquals("0 {$unit}", $result);
    }

    /**
     * @throws Exception
     *
     * @return void
     */
    public function testAgeShortcodeEmpty(): void
    {
        $this->assertEquals('', $this->age->handle());
    }

    /**
     * @throws \Maiorano\Shortcodes\Exceptions\RegisterException
     *
     * @return void
     */
    public function testIpsumShortcode(): void
    {
        $str = trim((string) preg_replace('/\s+/', ' ', '
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent laoreet eu nulla sit amet porttitor. Sed 
        accumsan nulla est, sit amet lobortis nunc convallis pretium. Phasellus aliquet euismod lacus, non maximus 
        odio pulvinar quis. Nulla eu lorem malesuada, aliquam risus sit amet, interdum ligula. Vivamus sollicitudin 
        bibendum accumsan. Maecenas sit amet ornare turpis, quis porttitor quam. Mauris turpis purus, bibendum in diam 
        in, rutrum accumsan arcu.
        '));
        $this->ipsum->alias('i');
        $this->assertSame($str, $this->ipsum->handle());
        $this->assertCount(2, $this->ipsum->getAlias());
    }

    /**
     * @throws \Maiorano\Shortcodes\Exceptions\RegisterException
     *
     * @return void
     */
    public function testSimpleShortcode(): void
    {
        $noContent = new Library\SimpleShortcode('alt');
        $noContent->alias('a');
        $this->assertInstanceOf(Contracts\AliasInterface::class, $this->simple);
        $this->assertInstanceOf(Contracts\AttributeInterface::class, $this->simple);
        $this->assertInstanceOf(Contracts\ContainerAwareInterface::class, $this->simple);
        $this->assertInstanceOf(Contracts\ShortcodeInterface::class, $this->simple);
        $this->assertEquals('simple', $this->simple->handle('test'));
        $this->assertEquals('test', $noContent->handle('test'));
        $this->assertCount(1, $noContent->getAlias());
    }

    /**
     * @return array
     */
    public function ageVariationProvider(): array
    {
        return [
            ['centuries'],
            ['decades'],
            ['years'],
            ['months'],
            ['days'],
            ['hours'],
            ['minutes'],
            ['seconds'],
        ];
    }
}
