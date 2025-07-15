<?php

namespace App\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class GetCollectionRequest
{
    public function __construct(
        #[Assert\NotBlank(message: 'Type must not be blank.')]
        #[Assert\Choice(choices: ['fruit', 'vegetable'], message: 'Type must be either "fruit" or "vegetable".')]
        public ?string             $type,

        #[Assert\Choice(choices: ['kg', 'g'], message: 'Unit must be either "kg" or "g".')]
        public ?string             $unit,

        #[Assert\Valid]
        public ?CollectionCriteria $criteria,

        #[Assert\Valid]
        public ?Pagination         $pagination,
    )
    {
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function getCriteria(): ?CollectionCriteria
    {
        return $this->criteria;
    }

    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }
}
