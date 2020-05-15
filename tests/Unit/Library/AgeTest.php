<?php

namespace Maiorano\Shortcodes\Test\Unit\Library;

use Exception;
use Maiorano\Shortcodes\Library\Age;
use PHPUnit\Framework\TestCase;

/**
 * Class AgeTest
 * @package Maiorano\Shortcodes\Test\Unit\Library
 */
class AgeTest extends TestCase
{
    /**
     * @var Age
     */
    private $age;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->age = new Age();
    }

    /**
     * @param string $unit
     *
     * @return void
     * @dataProvider unitProvider
     * @throws Exception
     *
     */
    public function testHandle(string $unit): void
    {
        $result = $this->age->handle('now', ['units' => $unit]);
        $this->assertEquals("0 {$unit}", $result);
    }

    /**
     * @return void
     * @throws Exception
     *
     */
    public function testHandleEmpty(): void
    {
        $this->assertEquals('', $this->age->handle());
    }

    /**
     * @return array
     */
    public function unitProvider(): array
    {
        return [
            ['centuries'],
            ['decades'],
            ['years'],
            ['months'],
            ['days'],
            ['hours'],
            ['minutes'],
            ['seconds'],
        ];
    }
}
