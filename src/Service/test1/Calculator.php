<?php

namespace App\Service\test1;

class Calculator
{
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }

     public function multiply(int $a, int $b): int
    {
        return $a * $b;
    }

    public function isPositive(int $number): bool
    {
        return $number > 0;
    }
}
