<?php

namespace Maiorano\Shortcodes\Test\Unit\Contracts;

use Maiorano\Shortcodes\Contracts;
use Maiorano\Shortcodes\Contracts\Traits;
use PHPUnit\Framework\TestCase;

/**
 * Class AttributeTest.
 */
class AttributeTest extends TestCase
{
    /**
     * @var Contracts\AttributeInterface
     */
    private $attribute;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->attribute = new class() implements Contracts\AttributeInterface {
            use Traits\Attribute;

            protected $name = 'attribute';
            protected $attributes = ['att' => 'default'];

            public function handle(?string $content = null, array $atts = []): string
            {
                return (string) $content;
            }
        };
    }

    /**
     * @return void
     */
    public function testGetAttributes(): void
    {
        $this->assertArrayHasKey('att', $this->attribute->getAttributes());
    }
}
