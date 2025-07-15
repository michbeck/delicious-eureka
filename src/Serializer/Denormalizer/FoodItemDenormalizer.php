<?php

namespace App\Serializer\Denormalizer;

use App\Entity\FoodItemInterface;
use App\Service\UnitConversion;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class FoodItemDenormalizer implements DenormalizerInterface
{
    /**
     * @see \App\Service\UnitConversion::UNIT_GRAM
     */
    public const string UNIT_GRAM = 'g';

    public function __construct(
        private readonly UnitConversion $unitConversion
    ) {}

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return is_a($type, FoodItemInterface::class, true);
    }

    public function denormalize($data, string $type, ?string $format = null, array $context = []): object
    {
        $quantity = $this->unitConversion->convert($data['quantity'], $data['unit'], self::UNIT_GRAM);

        return (new $type())
            ->setName($data['name'])
            ->setQuantity($quantity)
            ->setUnit('g');
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            FoodItemInterface::class => true,
        ];
    }
}