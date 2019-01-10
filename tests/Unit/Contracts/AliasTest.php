<?php

namespace Maiorano\Shortcodes\Test\Unit\Contracts;

use Maiorano\Shortcodes\Contracts;
use Maiorano\Shortcodes\Contracts\Traits;
use Maiorano\Shortcodes\Manager\ManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * Class AliasTest.
 */
class AliasTest extends TestCase
{
    /**
     * @var Contracts\AliasInterface
     */
    private $alias;
    /**
     * @var ManagerInterface|MockObject
     */
    private $manager;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->alias = new class() implements Contracts\AliasInterface {
            use Traits\Shortcode;
            use Traits\Alias;

            protected $name = 'alias';
            protected $alias = ['a'];

            public function handle(?string $content = null): string
            {
                return (string) $content;
            }
            public function alias(string $string): Contracts\AliasInterface
            {
                $this->aliasHelper($string);
                return $this;
            }
        };
        $this->manager = $this->createMock(ManagerInterface::class);
    }

    /**
     * @return void
     */
    public function testAlias(): void
    {
        $this->alias->alias('test');
        $this->assertCount(2, $this->alias->getAlias());
    }

    /**
     * @return void
     */
    public function testAliasContainerAware(): void
    {
        $aware = new class() implements Contracts\AliasInterface, Contracts\ContainerAwareInterface {
            use Traits\ContainerAware;
            use Traits\Shortcode;
            use Traits\Alias;

            private $name = 'alias';
            private $alias = ['a'];

            public function handle(?string $content = null): string
            {
                return (string) $content;
            }
            public function alias(string $string): Contracts\AliasInterface
            {
                $this->aliasHelper($string);
                return $this;
            }
        };
        $this->manager->expects($this->once())->method('isRegistered')->willReturn(false);
        $this->manager->expects($this->once())->method('register');

        $aware->bind($this->manager);
        $aware->alias('test');
        $this->assertCount(2, $aware->getAlias());
    }

    /**
     * @return void
     */
    public function testGetAlias(): void
    {
        $this->assertEquals('a', $this->alias->getAlias()[0]);
    }

    /**
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage You must provide a name for your shortcode
     */
    public function testAliasBlank(): void
    {
        $this->alias->alias('');
    }

    /**
     * @throws ReflectionException
     *
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage Cannot alias a shortcode that does not implement Maiorano\Shortcodes\Contracts\AliasInterface
     */
    public function testAliasNoAlias(): void
    {
        /**
         * @var Traits\Alias|MockObject
         */
        $bad = $this->getObjectForTrait(Traits\Alias::class);
        $bad->aliasHelper('bad');
    }
}
