<?php

namespace App\tests\Unit;

use PHPUnit\Framework\TestCase;// TestCase fournit les outils nécessaires pour écrire nos tests.
use App\Service\test1\Calculator;
use DivisionByZeroError;
use PHPUnit\Framework\Attributes\DataProvider;

class CalculatorTest extends TestCase
{
   Public function testAddition(): void
   {

        $result = 2 + 3;

        $this->assertEquals(10, $result);
   }

   public function testAddition2(): void
   {
    $calculator = new Calculator();
    $result = $calculator->add(2, 3);
    $this->assertEquals(5, $result);
   }
   public function testMultiplication(): void
   {
    $calculator=new Calculator();
    $result=$calculator->multiply(2,3);
    $this->assertEquals(7,$result);
   }

  #[DataProvider('positiveNumberProvider')]
public function testIsPositive(int $number, bool $expected): void
   {
    $positiveNumber=new Calculator();
    $result=$positiveNumber->isPositive($number);
    $this->assertSame($expected, $result);
   }

   public function testIsPositiveReturnsFalseForNegativeNumber(): void
   {
    $positiveNumber=new Calculator();
    $result=$positiveNumber->isPositive(0);
    $this->assertFalse($result);
   }

   #[DataProvider('divideNumber')]
   public function testDivide(int $a, int $b, float $expected): void
   {
    $calculator=new Calculator();
    $result=$calculator->divide($a,$b);
    $this->assertSame($expected, $result);
   }

   public static function divideNumber(): array
   {
     return [
        [10, 2, 5.0],
        [15, 3, 5.0],
        [20, 4, 5.0],
        [20, 4, 10.0],
     ];
    }
    public function testDivideByZero(): void
{
    $calculator = new Calculator();

    $this->expectException(DivisionByZeroError::class);

    $calculator->divide(10, 0);
}

   public static function positiveNumberProvider(): array
   {
     return [
        [5, true],
        [10, true],
        [0, false],
        [-5, false],
        [-10, false],
     ];
    }

}
