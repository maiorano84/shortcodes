<?php

namespace Maiorano\Shortcodes\Test\Unit\Library;

use Maiorano\Shortcodes\Contracts\AliasInterface;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Library\SimpleShortcode;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleShortcodeTest.
 */
class SimpleShortcodeTest extends TestCase
{
    /**
     * @var SimpleShortcode
     */
    private $simple;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->simple = new SimpleShortcode('simple', [], function () {
            return 'simple';
        });
    }

    /**
     * @throws RegisterException
     *
     * @return void
     */
    public function testAlias(): void
    {
        $this->assertInstanceOf(AliasInterface::class, $this->simple->alias('s'));
    }

    /**
     * @return void
     */
    public function testHandle(): void
    {
        $this->assertEquals('simple', $this->simple->handle('test'));
    }

    /**
     * @return void
     */
    public function testEmpty(): void
    {
        $noContent = new SimpleShortcode('alt');
        $this->assertEquals('test', $noContent->handle('test'));
    }
}
