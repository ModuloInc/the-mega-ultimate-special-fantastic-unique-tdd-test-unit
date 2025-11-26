<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Mathematique;
use App\Exception\DivisionByZeroException;

class MathTest extends TestCase
{
    private Mathematique $math;

    protected function setUp(): void
    {
        $this->math = new Mathematique();
    }

    public function testAdditionReturnsExpectedValues(): void
    {
        $a = 5;
        $b = 2;

        $result = $this->math->addition($a, $b);

        $this->assertEquals(7, $result, '5 + 2 doit être égal à 7');

        $a = 5.5;
        $b = 4.5;
        $result = $this->math->addition($a, $b);
        $this->assertEquals(10, $result, '5.5 + 4.5 doit être égal à 10');
    }

    public function testMultiplicationReturnsExpectedValues(): void
    {
        $a = 5;
        $b = 2;

        $result = $this->math->multiplication($a, $b);

        $this->assertEquals(10, $result, '5 × 2 doit être égal à 10');

        $a = 5.5;
        $b = 4;

        $result = $this->math->multiplication($a, $b);

        $this->assertEquals(22, $result, '5.5 × 4 doit être égal à 22');
    }

    public function testSoustractionReturnsExpectedValues(): void
    {
        $a = 5;
        $b = 2;

        $result = $this->math->soustraction($a, $b);

        $this->assertEquals(3, $result, '5 - 2 doit être égal à 3');

        $a = 5;
        $b = 6;

        $result = $this->math->soustraction($a, $b);

        $this->assertEquals(-1, $result, '5 - 6 doit être égal à -1');
    }

    public function testDivisionReturnsExpectedValues(): void
    {
        $a = 10;
        $b = 2;

        $result = $this->math->division($a, $b);

        $this->assertEquals(5, $result, '10 ÷ 2 doit être égal à 5');

        $a = 9;
        $b = 3;

        $result = $this->math->division($a, $b);

        $this->assertEquals(3, $result, '9 ÷ 3 doit être égal à 3');
    }

    public function testDivisionByZeroThrowsException(): void
    {
        $a = 10;
        $b = 0;

        $this->expectException(DivisionByZeroException::class);

        $this->math->division($a, $b);
    }
}
