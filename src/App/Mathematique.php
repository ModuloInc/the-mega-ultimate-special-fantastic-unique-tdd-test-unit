<?php

declare(strict_types=1);

namespace App;

use App\Exception\DivisionByZeroException;

class Mathematique
{
    public function addition(float $a, float $b): float
    {
        return $a + $b;
    }

    public function soustraction(float $a, float $b): float
    {
        return $a - $b;
    }

    public function multiplication(float $a, float $b): float
    {
        return $a * $b;
    }

    public function division(float $a, float $b): float
    {
        if ($b === 0.0) {
            throw new DivisionByZeroException('Division par zéro interdite');
        }

        return $a / $b;
    }
}

