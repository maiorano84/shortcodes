<?php

namespace Maiorano\Shortcodes\Test\Integration;

use Maiorano\Shortcodes\Exceptions\DeregisterException;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Manager\BaseManager;
use Maiorano\Shortcodes\Manager\ShortcodeManager;
use Maiorano\Shortcodes\Library\SimpleShortcode;
use PHPUnit\Framework\TestCase;

/**
 * Class ShortcodeManagerTest
 * @package Maiorano\Shortcodes\Test\Integration
 */
class ShortcodeManagerTest extends TestCase
{
    /**
     * @var array
     */
    private $library;
    /**
     * @var ShortcodeManager
     */
    private $manager;
    /**
     * @var string
     */
    private $dateFormat = 'l \t\h\e jS \o\f F, Y';

    /**
     * @return void
     */
    public function setUp(): void
    {
        $f = $this->dateFormat;

        $this->library = [
            'date' => new SimpleShortcode('date', null, function () use ($f) {
                return date($f);
            }),
            'mail' => new SimpleShortcode('mail', ['address'=>false], function($content, $atts){
                return sprintf('<a href="%s">%s</a>', $atts['address'] ? 'mailto:'.$atts['address'] : '#', $content);
            }),
            'bold' => new SimpleShortcode('bold', null, function($content){
                return sprintf('<strong>%s</strong>', $content);
            }),
        ];
        $this->manager = new ShortcodeManager($this->library);
    }

    /**
     * @return void
     */
    public function testManagerConstructor(): void
    {
        $this->assertCount(3, $this->manager);
    }

    /**
     * @throws RegisterException
     * @return void
     */
    public function testDoShortcode(): void
    {
        $date = date($this->dateFormat);
        $this->manager->alias('date', 'd');

        $this->assertSame($date, $this->manager->doShortcode('[date]'));
        $this->assertSame($date, $this->manager->doShortcode('[d]'));
        $this->assertSame('[d]', $this->manager->doShortcode('[d]', 'date'));
    }

    /**
     * @param string $original
     * @param string $alias
     * @param string $shortcode
     * @param string $expected
     * @throws RegisterException
     * @return void
     * @dataProvider aliasChainProvider
     */
    public function testAliasChain(string $original, string $alias, string $shortcode, string $expected): void
    {
        $result = $this->manager->alias($original, $alias)->doShortcode($shortcode);
        $this->assertSame($expected, $result);
    }

    /**
     * @param string $content
     * @param array|string $tags
     * @param bool $deep
     * @param string $expected
     * @throws RegisterException
     * @return void
     * @dataProvider nestedShortcodeProvider
     */
    public function testNestedShortcodePermissions(string $content, $tags, bool $deep, string $expected): void
    {
        $manager = $this->manager->alias('date', 'd');
        $nest = new SimpleShortcode('nest', null, function ($content) use ($manager) {
            return $manager->doShortcode($content, 'date');
        });

        $this->assertSame($expected, $this->manager->register($nest)->doShortcode($content, $tags, $deep));
    }

    /**
     * @throws DeregisterException
     * @return void
     */
    public function testManagerDeregister(): void
    {
        $this->manager->deregister('date');
        unset($this->manager['mail'], $this->manager['bold']);
        $this->assertCount(0, $this->manager);
    }

    /**
     * @throws RegisterException
     * @throws DeregisterException
     * @return void
     */
    public function testManagerDeregisterAlias(): void
    {
        $date = date($this->dateFormat);
        $manager = $this->manager->alias('date', 'd');
        $result = $manager->deregister('date', false)->doShortcode('Today is [d], not "[date]"');
        $this->assertSame("Today is {$date}, not \"[date]\"", $result);
    }

    /**
     * @return void
     */
    public function testUselessManager(): void
    {
        $manager = new class extends BaseManager{
            public function hasShortcode(string $content, $tags = []): bool{
                return false;
            }
            public function doShortcode(string $content, $tags = []): string{
                return 'Useless';
            }
        };

        $this->assertFalse($manager->hasShortcode('test'));
        $this->assertSame('Useless', $manager->doShortcode('test'));
    }

    /**
     * @return array
     */
    public function aliasChainProvider(): array
    {
        return [
            ['date', 'd', '[d]', date($this->dateFormat)],
            ['mail', 'm', '[m address=test@test.com]Test[/m]', '<a href="mailto:test@test.com">Test</a>'],
            ['mail', 'm', '[m]Empty[/m]', '<a href="#">Empty</a>'],
            ['bold', 'b', '[b]Bold[/b]', '<strong>Bold</strong>']
        ];
    }

    /**
     * @return array
     */
    public function nestedShortcodeProvider(): array
    {
        $date = date($this->dateFormat);
        $mail = '<a href="#">Mail</a>';
        $bold = '<strong>Bold</strong>';
        return [
            [
                '[nest]The date is [date], but "[mail]Mail[/mail]" doesn\'t work[/nest]',
                null, false,
                "The date is {$date}, but \"[mail]Mail[/mail]\" doesn't work",
            ],
            [
                '[nest]The date is [date], and "[mail]Mail[/mail]" still doesn\'t work.[/nest] Even so, my email is "[mail]Mail[/mail]"',
                'nest|mail', false,
                "The date is {$date}, and \"[mail]Mail[/mail]\" still doesn't work. Even so, my email is \"{$mail}\"",
            ],
            [
                '[nest]The date is [date], "[mail]Mail[/mail]" works, but [d] doesn\'t[/nest]',
                ['nest', 'mail'], true,
                "The date is {$date}, \"{$mail}\" works, but [d] doesn't",
            ],
            [
                '[nest]The date is [date], and "[mail]Mail[/mail]" works. Everything works. Even "[d]" and "[bold]Bold[/bold]"[/nest]',
                [], true,
                "The date is {$date}, and \"{$mail}\" works. Everything works. Even \"{$date}\" and \"{$bold}\"",
            ],
        ];
    }
}