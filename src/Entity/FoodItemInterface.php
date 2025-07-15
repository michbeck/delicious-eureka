<?php

namespace App\Entity;

use Symfony\Component\Uid\Uuid;

interface FoodItemInterface
{
    public function getId(): ?int;

    public function getUuid(): Uuid;

    public function getName(): string;

    public function setName(string $name): self;

    public function getQuantity(): float;

    public function setQuantity(float $quantity): self;

    public function getUnit(): string;

    public function setUnit(string $unit): self;
}
