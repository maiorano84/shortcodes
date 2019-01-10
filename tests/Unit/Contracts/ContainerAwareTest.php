<?php

namespace Maiorano\Shortcodes\Test\Unit\Contracts;

use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Library\SimpleShortcode;
use Maiorano\Shortcodes\Manager\ManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ContainerAwareTest.
 */
class ContainerAwareTest extends TestCase
{
    /**
     * @var SimpleShortcode
     */
    private $container;
    /**
     * @var ManagerInterface|MockObject
     */
    private $manager;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->container = new SimpleShortcode('container', [], function (?string $content = null, array $atts = []) {
            return (string) $content;
        });
        $this->manager = $this->createMock(ManagerInterface::class);
    }

    /**
     * @return void
     */
    public function testContainerIsBound(): void
    {
        $this->assertFalse($this->container->isBound());
    }

    /**
     * @return void
     */
    public function testContainerBind(): void
    {
        $this->container->bind($this->manager);
        $this->assertTrue($this->container->isBound());
    }

    /**
     * @return void
     */
    public function testContainerGetManager(): void
    {
        $this->container->bind($this->manager);
        $this->assertInstanceOf(ManagerInterface::class, $this->container->getManager());
    }

    /**
     * @throws RegisterException
     *
     * @return void
     */
    public function testHasShortcode(): void
    {
        $this->container->bind($this->manager);
        $this->manager
            ->expects($this->once())
            ->method('hasShortcode')
            ->willReturn(true);

        $this->assertTrue($this->container->hasShortcode('test'));
    }

    /**
     * @throws RegisterException
     *
     * @return void
     */
    public function testDoShortcode(): void
    {
        $this->container->bind($this->manager);
        $this->manager
            ->expects($this->once())
            ->method('doShortcode')
            ->willReturn('container');

        $this->assertEquals('container', $this->container->doShortcode('test'));
    }

    /**
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage No shortcode with identifier 'container' has been registered
     */
    public function testHasShortcodeMissing(): void
    {
        $this->container->hasShortcode('test');
    }

    /**
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage No shortcode with identifier 'container' has been registered
     */
    public function testDoShortcodeMissing(): void
    {
        $this->container->doShortcode('test');
    }
}
