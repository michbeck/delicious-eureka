<?php

namespace App\Service;

use App\Exception\UnitConversionException;

class UnitConversion implements UnitConversionInterface
{
    public const string UNIT_GRAM = 'g';

    public const string UNIT_KILOGRAM = 'kg';

    protected const array CONVERSION_FACTORS_TO_GRAMS = [
        self::UNIT_GRAM => 1.0,
        self::UNIT_KILOGRAM => 1000.0,
    ];

    public function convert(float $quantity, string $fromUnit, string $toUnit): float
    {
        $fromUnit = $this->normalizeUnit($fromUnit);
        $toUnit = $this->normalizeUnit($toUnit);
        $quantityInGrams = $quantity * self::CONVERSION_FACTORS_TO_GRAMS[$fromUnit];

        return $quantityInGrams / self::CONVERSION_FACTORS_TO_GRAMS[$toUnit];
    }

    protected function normalizeUnit(string $unit): string
    {
        $unit = strtolower(trim($unit));

        if (!isset(self::CONVERSION_FACTORS_TO_GRAMS[$unit])) {
            throw new UnitConversionException("Unsupported unit `$unit` detected.");
        }

        return $unit;
    }
}
