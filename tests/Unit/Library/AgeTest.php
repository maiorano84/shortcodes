<?php

namespace Maiorano\Shortcodes\Test\Unit\Library;

use Exception;
use Maiorano\Shortcodes\Library\Age;
use PHPUnit\Framework\TestCase;

/**
 * Class AgeTest.
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
     * @throws Exception
     *
     * @return void
     * @dataProvider unitProvider
     */
    public function testHandle(string $unit): void
    {
        $result = $this->age->handle('now', ['units' => $unit]);
        $this->assertEquals("0 {$unit}", $result);
    }

    /**
     * @throws Exception
     *
     * @return void
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
