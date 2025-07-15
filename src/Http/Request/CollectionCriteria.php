<?php

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class CollectionCriteria
{
    private function __construct(
        #[Assert\GreaterThanOrEqual(value: 0, message: 'minQuantity must be 0 or greater.')]
        protected ?int $minQuantity,

        #[Assert\GreaterThanOrEqual(value: 0, message: 'maxQuantity must be 0 or greater.')]
        protected ?int $maxQuantity
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        $minQuantity = $request->query->get('minQuantity');
        $maxQuantity = $request->query->get('maxQuantity');

        return new self($minQuantity, $maxQuantity);
    }

    public function getMinQuantity(): ?int
    {
        return $this->minQuantity;
    }

    public function getMaxQuantity(): ?int
    {
        return $this->maxQuantity;
    }
}
