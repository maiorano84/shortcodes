<?php

namespace Maiorano\Shortcodes\Test;

use Maiorano\Shortcodes\Contracts;
use Maiorano\Shortcodes\Exceptions\RegisterException;
use Maiorano\Shortcodes\Exceptions\DeregisterException;
use Maiorano\Shortcodes\Manager\ShortcodeManager;
use Maiorano\Shortcodes\Parsers\ParserInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ShortcodeManagerTest
 * @package Maiorano\Shortcodes\Test
 */
class ShortcodeManagerTest extends TestCase
{
    /**
     * @var ParserInterface|MockObject
     */
    private $parser;

    /**
     * @var Contracts\ShortcodeInterface|MockObject
     */
    private $shortcode;

    /**
     * @var Contracts\AttributeInterface|MockObject
     */
    private $attribute;

    /**
     * @var Contracts\ContainerAwareInterface|MockObject
     */
    private $containerAware;

    /**
     * @var Contracts\AliasInterface|MockObject
     */
    private $alias;

    /**
     * @var ShortcodeManager
     */
    private $manager;

    /**
     * @return void
     * @throws RegisterException
     */
    public function setUp(): void
    {
        $this->parser = $this->createMock(ParserInterface::class);
        $this->shortcode = $this->createMock(Contracts\ShortcodeInterface::class);
        $this->attribute = $this->createMock(Contracts\AttributeInterface::class);
        $this->containerAware = $this->createMock(Contracts\ContainerAwareInterface::class);
        $this->alias = $this->createMock(Contracts\AliasInterface::class);
        $this->manager = new ShortcodeManager([], $this->parser);
    }

    /**
     * @return void
     * @throws RegisterException
     */
    public function testRegister(): void
    {
        $this->shortcode
            ->expects($this->once())
            ->method('getName')
            ->willReturn('test');

        $this->manager->register($this->shortcode);

        $this->assertArrayHasKey('test', $this->manager);
    }

    /**
     * @return void
     */
    public function testRegisterShorthand(): void
    {
        $this->shortcode
            ->expects($this->once())
            ->method('getName')
            ->willReturn('test');

        $this->manager[5] = $this->shortcode;
        $this->manager['t'] = $this->shortcode;

        $this->assertCount(2, $this->manager);
        $this->assertArrayHasKey('test', $this->manager);
        $this->assertArrayHasKey('t', $this->manager);
    }

    /**
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage You must provide a name for your shortcode
     */
    public function testRegisterExceptionBlank(): void
    {
        $this->manager->register($this->shortcode, '');
    }

    /**
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage The shortcode 'test' has already been registered
     */
    public function testRegisterExceptionDuplicate(): void
    {
        $this->shortcode
            ->expects($this->exactly(2))
            ->method('getName')
            ->willReturn('test');
        $this->manager->register($this->shortcode);
        $this->manager->register($this->shortcode);
    }

    /**
     * @return void
     * @throws RegisterException
     */
    public function testRegisterAll(): void
    {
        $library = [
            'shortcode' => $this->shortcode,
            'attribute' => $this->attribute,
            'containerAware' => $this->containerAware,
            'alias' => $this->alias
        ];
        $this->containerAware
            ->expects($this->once())
            ->method('bind');
        $this->alias
            ->expects($this->once())
            ->method('getAlias')
            ->willReturn(['a']);

        $this->manager->registerAll($library);
        $this->assertCount(5, $this->manager);
        $this->assertArrayHasKey('a', $this->manager);
    }

    /**
     * @return void
     * @throws DeregisterException
     */
    public function testDeregister(): void
    {
        $this->alias
            ->expects($this->exactly(2))
            ->method('getName')
            ->willReturn('alias');
        $this->alias
            ->expects($this->exactly(2))
            ->method('getAlias')
            ->willReturn(['a', 'b', 'c']);

        $this->manager[] = $this->alias;
        $this->manager->deregister('alias');
        $this->assertCount(0, $this->manager);
    }

    /**
     * @return void
     */
    public function testDeregisterShorthand(): void
    {
        $this->alias
            ->expects($this->exactly(2))
            ->method('getName')
            ->willReturn('alias');
        $this->alias
            ->expects($this->exactly(2))
            ->method('getAlias')
            ->willReturn(['a', 'b', 'c']);

        $this->manager[] = $this->alias;
        unset($this->manager['alias']);
        $this->assertCount(0, $this->manager);
    }

    /**
     * @return void
     * @throws DeregisterException
     */
    public function testDeregisterAlias(): void
    {
        $this->alias
            ->expects($this->exactly(2))
            ->method('getName')
            ->willReturn('alias');
        $this->alias
            ->expects($this->once())
            ->method('getAlias')
            ->willReturn(['a', 'b', 'c']);

        $this->manager[] = $this->alias;
        $this->manager->deregister('b');
        $this->assertCount(3, $this->manager);
        $this->assertArrayNotHasKey('b', $this->manager);
    }

    /**
     * @return void
     * @throws DeregisterException
     */
    public function testDeregisterOnlyPrimary(): void
    {
        $this->alias
            ->expects($this->exactly(2))
            ->method('getName')
            ->willReturn('alias');
        $this->alias
            ->expects($this->once())
            ->method('getAlias')
            ->willReturn(['a', 'b', 'c']);

        $this->manager[] = $this->alias;
        $this->manager->deregister('alias', false);
        $this->assertCount(3, $this->manager);
        $this->assertArrayNotHasKey('alias', $this->manager);
    }

    /**
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\DeregisterException
     * @expectedExceptionMessage The shortcode 'test' does not exist in the current library
     */
    public function testDeregisterMissing(): void
    {
        $this->manager->deregister('test');
    }

    /**
     * @return void
     */
    public function testIsRegistered(): void
    {
        $this->shortcode
            ->expects($this->once())
            ->method('getName')
            ->willReturn('test');
        $this->manager[] = $this->shortcode;

        $this->assertFalse($this->manager->isRegistered('empty'));
        $this->assertTrue($this->manager->isRegistered('test'));
    }

    /**
     * @return void
     */
    public function testIsRegisteredShorthand(): void
    {
        $this->shortcode
            ->expects($this->once())
            ->method('getName')
            ->willReturn('test');
        $this->manager[] = $this->shortcode;

        $this->assertFalse(isset($this->manager['empty']));
        $this->assertTrue(isset($this->manager['test']));
        $this->assertTrue(empty($this->manager['empty']));
        $this->assertFalse(empty($this->manager['test']));
    }

    /**
     * @return void
     */
    public function testGetRegistered(): void
    {
        $this->manager['test'] = $this->shortcode;
        $this->manager['t'] = $this->shortcode;

        $this->assertEquals(['test', 't'], $this->manager->getRegistered());
    }

    /**
     * @return void
     * @throws RegisterException
     */
    public function testGetShortcodeShorthand(): void
    {
        $this->manager->register($this->shortcode, 'test');
        $this->assertSame($this->manager['test'], $this->shortcode);
    }

    /**
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage No shortcode with identifier 'test' has been registered
     */
    public function testGetShortcodeShorthandMissing(): void
    {
        $this->manager['test'];
    }

    /**
     * @return void
     * @throws RegisterException
     */
    public function testAlias(): void
    {
        $this->alias
            ->expects($this->once())
            ->method('alias');
        $this->manager['test'] = $this->alias;
        $this->manager->alias('test', 't');
        $this->assertCount(2, $this->manager);
    }

    /**
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage No shortcode with identifier 'test' has been registered
     */
    public function testAliasMissing(): void
    {
        $this->manager->alias('test', 't');
    }

    /**
     * @return void
     * @expectedException \Maiorano\Shortcodes\Exceptions\RegisterException
     * @expectedExceptionMessage Cannot alias a shortcode that does not implement Maiorano\Shortcodes\Contracts\AliasInterface
     */
    public function testAliasNoAlias(): void
    {
        $this->manager['test'] = $this->shortcode;
        $this->manager->alias('test', 't');
    }

    /**
     * @return void
     * @throws RegisterException
     */
    public function testHasShortcode(): void
    {
        $this->manager->registerAll([
            'foo' => $this->shortcode,
            'bar' => $this->shortcode,
            'baz' => $this->shortcode,
        ]);

        $this->parser
            ->expects($this->exactly(3))
            ->method('parseShortcode')
            ->will($this->returnValueMap([
                ['[qux]', ['foo', 'bar', 'baz'], null, []],
                ['[foo]', ['foo', 'bar', 'baz'], null, [['tag' => 'foo']]],
                ['[foo]', ['bar', 'baz'], null, []]
            ]));

        $this->assertFalse($this->manager->hasShortcode('[qux]'));
        $this->assertTrue($this->manager->hasShortcode('[foo]'));
        $this->assertFalse($this->manager->hasShortcode('[foo]', 'bar|baz'));
    }

    /**
     * @param string $shortcode
     * @param string $content
     * @param string $expected
     * @return void
     * @dataProvider shortcodeVariationProvider
     */
    public function testDoShortcode($shortcode, $content, $expected): void
    {
        $this->parser
            ->expects($this->once())
            ->method('parseShortcode')
            ->will($this->returnCallback(function($content, $tags, \Closure $callback) use ($shortcode){
                return $callback($shortcode, '', []);
            }));

        $this->{$shortcode}
            ->expects($this->once())
            ->method('handle')
            ->willReturn($expected);

        $this->manager[$shortcode] = $this->{$shortcode};

        $this->assertEquals($expected, $this->manager->doShortcode($content));
    }

    /**
     * @return void
     * @throws RegisterException
     */
    public function testDoShortcodeNested(): void
    {
        $this->parser
            ->expects($this->at(0))
            ->method('parseShortcode')
            ->willReturn('[bar]');
        $this->parser
            ->expects($this->at(1))
            ->method('parseShortcode')
            ->willReturn(['tag' => 'bar', 'content' => '']);
        $this->parser
            ->expects($this->at(2))
            ->method('parseShortcode')
            ->willReturn('bar');

        $this->manager->registerAll([
            'foo' => $this->shortcode,
            'bar' => $this->shortcode,
            'baz' => $this->shortcode,
        ]);

        $this->assertEquals('bar', $this->manager->doShortcode('[foo][bar][/foo]', [], true));
    }

    /**
     * @return array
     */
    public function shortcodeVariationProvider(): array
    {
        return [
            ['shortcode', '[shortcode]Content[/shortcode]', 'Content'],
            ['attribute', '[attributes test]Content[/attributes]', 'Content']
        ];
    }
}
