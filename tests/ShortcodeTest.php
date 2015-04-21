<?php

namespace Maiorano\WPShortcodes\Test;

use Maiorano\WPShortcodes\Manager\ShortcodeManager;
use Maiorano\WPShortcodes\Shortcode\SimpleShortcode;
use Maiorano\WPShortcodes\Examples\Age;

class ShortcodeTest extends TestCase{
    public function testSimpleShortcodeContent()
    {
        $manager = new ShortcodeManager(array(
            'foo'=>new SimpleShortcode('foo'),
            'bar'=>new SimpleShortcode('bar'),
            'baz'=>new SimpleShortcode('baz')
        ));

        $content = '[foo]Some text to [bar]display[/bar] [baz]when matched[/baz]';
        $this->assertEquals($manager->doShortcode($content), 'Some text to display when matched');
        $this->assertEquals($manager->doShortcode('[qux]Unmatched[/qux]'), '[qux]Unmatched[/qux]');

    }
    public function testSimpleShortcodeAttributes()
    {
        $manager = new ShortcodeManager();
        $foo = new SimpleShortcode('foo', array('bar'=>'baz'), function($atts, $content){
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
        $content = '[age units=seconds]Now[/age]';
        $this->assertEquals($manager->doShortcode($content), '0 seconds');
    }
}