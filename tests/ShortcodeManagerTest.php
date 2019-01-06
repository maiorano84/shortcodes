<?php

namespace Maiorano\Shortcodes\Test;

use PHPUnit\Framework\TestCase;
use Maiorano\Shortcodes\Manager\ShortcodeManager;
use Maiorano\Shortcodes\Library\SimpleShortcode;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Exceptions\DeregisterException;

/**
 * Class ShortcodeManagerTest
 * @package Maiorano\Shortcodes\Test
 */
class ShortcodeManagerTest extends TestCase
{
    /**
     * @var ShortcodeManager
     */
    private $manager;

    /**
     *
     */
    public function setUp()
    {
        $this->manager = new ShortcodeManager();
    }

    /**
     * @throws RegisterException
     * @throws DeregisterException
     */
    public function testShortcodeRegisterDeregister()
    {
        $test = new SimpleShortcode('test');

        $this->manager->register($test);
        $this->assertTrue(isset($this->manager['test']));

        $this->manager->deregister('test');
        $this->assertTrue(empty($this->manager['test']));
    }

    /**
     *
     */
    public function testHasShortcode()
    {
        $this->manager->registerAll([
            'foo' => new SimpleShortcode('foo'),
            'bar' => new SimpleShortcode('bar'),
            'baz' => new SimpleShortcode('baz')
        ]);

        $this->assertTrue($this->manager->hasShortcode('[foo]'));
        $this->assertTrue($this->manager->hasShortcode('[foo][bar/][foo]', 'bar'));
        $this->assertFalse($this->manager->hasShortcode('[foo]', 'bar'));
    }

    /**
     * @throws RegisterException
     */
    public function testAlias()
    {
        $this->manager->register(new SimpleShortcode('foo'));
        $this->manager->alias('foo', 'f');
        $this->assertEquals($this->manager['foo'], $this->manager['f']);
    }

    /**
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage You must provide a name for your shortcode
     */
    public function testEmptyName()
    {
        $this->manager->register(new SimpleShortcode(''));
    }

    /**
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage The shortcode 'test' has already been registered
     */
    public function testRegisterError()
    {
        $test = new SimpleShortcode('test');
        $this->manager['test'] = $test;
        $this->manager->register($test);
    }

    /**
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage No shortcode with identifier 'test' has been registered
     */
    public function testMissing()
    {
        $var = $this->manager['test'];
    }

    /**
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage No shortcode with identifier 'test' has been registered
     */
    public function testAliasMissing()
    {
        $this->manager->alias('test', 't');
    }

    /**
     * @expectedException \Maiorano\Shortcodes\Exceptions\DeregisterException
     * @expectedExceptionMessage The shortcode 'test' does not exist in the current library
     */
    public function testDeregisterError()
    {
        $this->manager->deregister('test');
    }
}
