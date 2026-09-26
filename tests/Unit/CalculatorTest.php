<?php

namespace App\tests\Unit;

use PHPUnit\Framework\TestCase; // TestCase fournit les outils nécessaires pour écrire nos tests.
use App\Service\test1\Calculator;
use DivisionByZeroError;
use PHPUnit\Framework\Attributes\DataProvider;

class CalculatorTest extends TestCase
{
    public function testAddition(): void
    {
        // Test d'une addition simple avec PHP directement
        $result = 2 + 3;

        // On vérifie que le résultat attendu est bien 5
        $this->assertEquals(5, $result);
    }

    public function testAddition2(): void
    {
        // Création d'une instance de notre service Calculator
        $calculator = new Calculator();

        // Appel de la méthode add()
        $result = $calculator->add(2, 3);

        // Vérification du résultat
        $this->assertEquals(5, $result);
    }

    public function testMultiplication(): void
    {
        $calculator = new Calculator();

        // 2 × 3 = 6
        $result = $calculator->multiply(2, 3);

        // Le résultat attendu est donc 6
        $this->assertEquals(6, $result);
    }

    #[DataProvider('positiveNumberProvider')]
    public function testIsPositive(int $number, bool $expected): void
    {
        $positiveNumber = new Calculator();

        $result = $positiveNumber->isPositive($number);

        // Vérifie que le résultat correspond à la valeur attendue
        $this->assertSame($expected, $result);
    }

    public function testIsPositiveReturnsFalseForNegativeNumber(): void
    {
        $positiveNumber = new Calculator();

        // 0 n'est pas un nombre positif
        $result = $positiveNumber->isPositive(0);

        $this->assertFalse($result);
    }

    #[DataProvider('divideNumber')]
    public function testDivide(int $a, int $b, float $expected): void
    {
        $calculator = new Calculator();

        $result = $calculator->divide($a, $b);

        // Vérification du résultat de la division
        $this->assertSame($expected, $result);
    }

    public static function divideNumber(): array
    {
        return [
            [10, 2, 5.0],
            [15, 3, 5.0],
            [20, 4, 5.0],
        ];
    }

    public function testDivideByZero(): void
    {
        $calculator = new Calculator();

        // On indique que l'appel suivant doit provoquer une exception
        $this->expectException(DivisionByZeroError::class);

        // Une division par zéro doit donc déclencher l'exception
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
