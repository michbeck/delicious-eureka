<?php

namespace App\Service;

interface UnitConversionInterface
{
    public function convert(float $quantity, string $fromUnit, string $toUnit): float;
}
