<?php

namespace Maiorano\Shortcodes\Test\Unit\Library;

use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Library\Ipsum;
use PHPUnit\Framework\TestCase;

/**
 * Class IpsumTest.
 */
class IpsumTest extends TestCase
{
    /**
     * @var Ipsum
     */
    private $ipsum;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->ipsum = new Ipsum();
    }

    /**
     * @return void
     */
    public function testHandle(): void
    {
        $str = trim((string) preg_replace('/\s+/', ' ', '
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent laoreet eu nulla sit amet porttitor. Sed 
        accumsan nulla est, sit amet lobortis nunc convallis pretium. Phasellus aliquet euismod lacus, non maximus 
        odio pulvinar quis. Nulla eu lorem malesuada, aliquam risus sit amet, interdum ligula. Vivamus sollicitudin 
        bibendum accumsan. Maecenas sit amet ornare turpis, quis porttitor quam. Mauris turpis purus, bibendum in diam 
        in, rutrum accumsan arcu.
        '));

        $this->assertSame($str, $this->ipsum->handle());
    }

    /**
     * @throws RegisterException
     *
     * @return void
     */
    public function testAlias(): void
    {
        $this->assertInstanceOf(AliasInterface::class, $this->ipsum->alias('i'));
    }
}
