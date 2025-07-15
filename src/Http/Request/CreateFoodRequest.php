<?php

namespace App\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateFoodRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'ID must not be blank.')]
        #[Assert\Positive(message: 'ID must be a positive number.')]
        public ?int $id,

        #[Assert\NotBlank(message: 'Name must not be blank.')]
        #[Assert\Length(min: 2, max: 150, minMessage: 'Name must be at least {{ limit }} characters.', maxMessage: 'Name must not exceed {{ limit }} characters.')]
        public ?string $name,

        #[Assert\NotBlank(message: 'Type must not be blank.')]
        #[Assert\Choice(choices: ['fruit', 'vegetable'], message: 'Type must be either "fruit" or "vegetable".')]
        public ?string $type,

        #[Assert\NotBlank(message: 'Quantity must not be blank.')]
        #[Assert\Type(type: 'numeric', message: 'Quantity must be a number.')]
        #[Assert\GreaterThanOrEqual(0, message: 'Quantity must be zero or greater.')]
        public float|int|null $quantity,

        #[Assert\NotBlank(message: 'Unit must not be blank.')]
        #[Assert\Choice(choices: ['kg', 'g'], message: 'Unit must be either "kg" or "g".')]
        public ?string $unit
    )
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }
}
