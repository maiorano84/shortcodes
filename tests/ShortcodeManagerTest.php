<?php
namespace Maiorano\Shortcodes\Test;

use Maiorano\Shortcodes\Manager\ShortcodeManager;
use Maiorano\Shortcodes\Library\SimpleShortcode;

class ShortcodeManagerTest extends TestCase
{
    public function testShortcodeRegisterDeregister()
    {
        $manager = new ShortcodeManager();
        $test = new SimpleShortcode('test');

        $manager->register($test);
        $this->assertTrue(isset($manager['test']));

        $manager->deregister('test');
        $this->assertTrue(empty($manager['test']));
    }

    public function testHasShortcode()
    {
        $manager = new ShortcodeManager(array(
            'foo' => new SimpleShortcode('foo'),
            'bar' => new SimpleShortcode('bar'),
            'baz' => new SimpleShortcode('baz')
        ));

        $content = '[foo]';
        $this->assertTrue($manager->hasShortcode($content)); //Check all registered
        $this->assertTrue($manager->hasShortcode($content, 'foo')); //Check a single tag
        $this->assertTrue($manager->hasShortcode($content, array('foo', 'bar'))); //Check a set of tags
        $this->assertFalse($manager->hasShortcode($content, 'bar|baz')); //Check a set of tags as a string
        $this->assertFalse($manager->hasShortcode($content, 'none|exist')); //Check for non-existent tags
    }

    /**
     * @expectedException Maiorano\Shortcodes\Exceptions\ShortcodeRegisterException
     * @expectedExceptionMessage The shortcode 'test' has already been registered
     */
    public function testRegisterError()
    {
        $manager = new ShortcodeManager();
        $test = new SimpleShortcode('test');
        $manager['test'] = $test;
        $manager->register($test);
    }

    /**
     * @expectedException Maiorano\Shortcodes\Exceptions\ShortcodeRegisterException
     * @expectedExceptionMessage No shortcode with identifier 'test' has been registered
     */
    public function testMissing()
    {
        $manager = new ShortcodeManager();
        $var = $manager['test'];
    }

    /**
     * @expectedException Maiorano\Shortcodes\Exceptions\ShortcodeDeregisterException
     * @expectedExceptionMessage The shortcode 'test' does not exist in the current library
     */
    public function testDeregisterError()
    {
        $manager = new ShortcodeManager();
        $manager->deregister('test');
    }
}
