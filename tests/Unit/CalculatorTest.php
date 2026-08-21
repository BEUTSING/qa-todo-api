<?php

namespace App\tests\Unit;

use PHPUnit\Framework\TestCase;// TestCase fournit les outils nécessaires pour écrire nos tests.
use App\Service\test1\Calculator;

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
    $this->assertEquals(6,$result);
   }

}
