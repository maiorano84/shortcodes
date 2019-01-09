<?php

namespace Maiorano\Shortcodes\Test\Contracts;

use Maiorano\Shortcodes\Contracts;
use Maiorano\Shortcodes\Contracts\Traits;
use PHPUnit\Framework\TestCase;

/**
 * Class ShortcodeTest
 * @package Maiorano\Shortcodes\Test\Contracts
 */
class ShortcodeTest extends TestCase
{
    /**
     * @var Contracts\ShortcodeInterface
     */
    private $shortcode;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->shortcode = new class implements Contracts\ShortcodeInterface
        {
            use Traits\Shortcode;

            protected $name = 'shortcode';

            public function handle(?string $content = null): string
            {
                return (string)$content;
            }
        };
    }

    /**
     * @return void
     */
    public function testGetName(): void
    {
        $this->assertEquals('shortcode', $this->shortcode->getName());
    }
}