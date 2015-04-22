<?php
namespace Maiorano\Shortcodes\Test;

use Maiorano\Shortcodes\Manager\ShortcodeManager;
use Maiorano\Shortcodes\Shortcode\SimpleShortcode;
use Maiorano\Shortcodes\Examples\Age;
use Maiorano\Shortcodes\Examples\Ipsum;

class ShortcodeTest extends TestCase
{
    public function testSimpleShortcodeContent()
    {
        $manager = new ShortcodeManager(array(
            'foo' => new SimpleShortcode('foo'),
            'bar' => new SimpleShortcode('bar'),
            'baz' => new SimpleShortcode('baz')
        ));

        $content = '[foo]Some text to [bar]display[/bar] [baz]when matched[/baz]';
        $this->assertEquals($manager->doShortcode($content), 'Some text to display when matched');
        $this->assertEquals($manager->doShortcode('[qux]Unmatched[/qux]'), '[qux]Unmatched[/qux]');
    }

    public function testSimpleShortcodeAttributes()
    {
        $manager = new ShortcodeManager();
        $foo = new SimpleShortcode('foo', array('bar' => 'baz'), function ($content, $atts) {
            return $content ?: $atts['bar'];
        });

        $manager->register($foo);
        $content = '[foo]Foo shortcode[/foo] can also yield [foo bar=bar] and [foo]';
        $this->assertEquals($manager->doShortcode($content), 'Foo shortcode can also yield bar and baz');
    }

    public function testCustomShortcode()
    {
        $manager = new ShortcodeManager();
        $manager->register(new Age);
        $this->assertEquals($manager->doShortcode('[age]Now[/age]'), '0 years');
        $this->assertEquals($manager->doShortcode('[age units=seconds]Now[/age]'), '0 seconds');
    }

    public function testCustomShortcodeNoAttributes()
    {
        $manager = new ShortcodeManager();
        $manager->register(new Ipsum);
        $this->assertEquals($manager->doShortcode('[ipsum]'), $manager['ipsum']->getIpsum());
    }

    public function testEscapedShortcode()
    {
        $manager = new ShortcodeManager();
        $manager->register(new Ipsum);
        $this->assertEquals($manager->doShortcode('[[ipsum]]'), '[ipsum]');
    }
}