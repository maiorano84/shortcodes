<?php
namespace Maiorano\Shortcodes\Test;

use Maiorano\Shortcodes\Manager\ShortcodeManager;
use Maiorano\Shortcodes\Library;

class ShortcodeTest extends TestCase
{
    public function testSimpleShortcodeContent()
    {
        $manager = new ShortcodeManager(array(
            'foo' => new Library\SimpleShortcode('foo'),
            'bar' => new Library\SimpleShortcode('bar'),
            'baz' => new Library\SimpleShortcode('baz')
        ));

        $content = '[foo]Some text to [bar]display[/bar] [baz]when matched[/baz]';
        $this->assertEquals($manager->doShortcode($content), 'Some text to display when matched');
        $this->assertEquals($manager->doShortcode('[qux]Unmatched[/qux]'), '[qux]Unmatched[/qux]');
    }

    public function testSimpleShortcodeAttributes()
    {
        $manager = new ShortcodeManager();
        $foo = new Library\SimpleShortcode('foo', array('bar' => 'baz'), function ($content, $atts) {
            return $content ?: $atts['bar'];
        });

        $manager->register($foo);
        $content = '[foo]Foo shortcode[/foo] can also yield [foo bar=bar] and [foo]';
        $this->assertEquals($manager->doShortcode($content), 'Foo shortcode can also yield bar and baz');
    }

    public function testCustomShortcode()
    {
        $manager = new ShortcodeManager();
        $manager->register(new Library\Age);
        $this->assertEquals($manager->doShortcode('[age]Now[/age]'), '0 years');
        $this->assertEquals($manager->doShortcode('[age units=seconds]Now[/age]'), '0 seconds');
    }

    public function testCustomShortcodeNoAttributes()
    {
        $manager = new ShortcodeManager();
        $manager->register(new Library\Ipsum);
        $this->assertEquals($manager->doShortcode('[ipsum]'), $manager['ipsum']->getIpsum());
    }

    /**
     * @todo Determine if segfault only occurs on local environment
     */
    public function testNestedShortcode()
    {
        $manager = new ShortcodeManager(array(
            'foo' => new Library\SimpleShortcode('foo', null, function ($content) {
                return 'foo' . $this->manager->doShortcode($content, 'bar');
            }),
            'bar' => new Library\SimpleShortcode('bar', null, function ($content) {
                return 'bar' . $content;
            }),
            'baz' => new Library\SimpleShortcode('baz', null, function () {
                return 'baz';
            })
        ));

        //Selective
        $this->assertEquals($manager->doShortcode('[foo][bar/][/foo]'), 'foobar');
        $this->assertNotEquals($manager->doShortcode('[foo][baz/][/foo]'), 'foobaz');

        //Permissive
        $this->assertEquals($manager->doShortcode('[foo][baz/][/foo]', 'foo|baz', true), 'foobaz');
        //$this->assertNotEquals($manager->doShortcode('[foo][baz/][/foo]', 'foo', true), 'foobaz'); //Segfault

        //I DO WHAT I WANT
        $this->assertEquals($manager->doShortcode('[foo][bar][baz/][/bar][/foo]', null, true), 'foobarbaz');
    }

    public function testEscapedShortcode()
    {
        $manager = new ShortcodeManager();
        $manager->register(new Library\Ipsum);
        $this->assertEquals($manager->doShortcode('[[ipsum]]'), '[ipsum]');
    }
}