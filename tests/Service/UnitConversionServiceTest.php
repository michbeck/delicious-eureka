<?php

namespace App\Tests\Service;

use App\Exception\UnitConversionException;
use App\Service\UnitConversion;
use PHPUnit\Framework\TestCase;

class UnitConversionServiceTest extends TestCase
{
    private UnitConversion $unitConversionService;

    protected function setUp(): void
    {
        $this->unitConversionService = new UnitConversion();
    }

    public function testConvertReturnsCorrectGramValue(): void
    {
        $this->assertSame(1000.0, $this->unitConversionService->convert(1, 'kg', 'g'));
        $this->assertSame(500000.0, $this->unitConversionService->convert(500, 'kg', 'g'));
        $this->assertSame(1000.0, $this->unitConversionService->convert(1, 'KG', 'g'));
        $this->assertSame(250000.0, $this->unitConversionService->convert(250, 'kg', 'G'));
    }

    public function testConvertReturnsCorrectKilogramValue(): void
    {
        $this->assertSame(1.0, $this->unitConversionService->convert(1000, 'g', 'kg'));
        $this->assertSame(0.5, $this->unitConversionService->convert(500, 'g', 'kg'));
        $this->assertSame(2.0, $this->unitConversionService->convert(2000, 'G', 'KG'));
        $this->assertSame(0.1, $this->unitConversionService->convert(100, 'g', 'KG'));
    }

    public function testConvertThrowsExceptionWhileFromUnitIsUnsupported(): void
    {
        $this->expectException(UnitConversionException::class);
        $this->expectExceptionMessage('Unsupported unit `lb` detected.');
        $this->unitConversionService->convert(5, 'lb', 'g');
    }

    public function testConvertThrowsExceptionWhileToUnitIsUnsupported(): void
    {
        $this->expectException(UnitConversionException::class);
        $this->expectExceptionMessage('Unsupported unit `lb` detected.');
        $this->unitConversionService->convert(5, 'kg', 'lb');
    }

    public function testConvertZeroQuantity(): void
    {
        $this->assertSame(0.0, $this->unitConversionService->convert(0, 'g', 'kg'));
    }

    public function testConvertSameUnitReturnsSameQuantity(): void
    {
        $this->assertSame(123.45, $this->unitConversionService->convert(123.45, 'kg', 'kg'));
    }

}
