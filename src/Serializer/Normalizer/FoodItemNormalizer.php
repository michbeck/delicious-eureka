<?php

namespace App\Serializer\Normalizer;

use App\Entity\FoodItemInterface;
use App\Service\UnitConversion;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FoodItemNormalizer implements NormalizerInterface
{
    public function __construct(
        protected UnitConversion      $unitConversion,
        protected NormalizerInterface $objectNormalizer
    ) {}

    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        $normalized = $this->objectNormalizer->normalize($data, $format, $context);

        $unit = strtolower(trim($context['unit'] ?? $data->getUnit()));
        if ($unit !== $data->getUnit()) {
            $normalized['quantity'] = $this->unitConversion->convert($data->getQuantity(), $data->getUnit(), $unit);
            $normalized['unit'] = $unit;
        }

        return $normalized;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof FoodItemInterface;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            FoodItemInterface::class => true,
        ];
    }
}