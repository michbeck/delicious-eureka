<?php

namespace App\Http\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class Pagination
{
    public const int PAGINATION_CAP_LIMIT = 100;

    private function __construct(
        #[Assert\GreaterThanOrEqual(value: 0, message: 'Offset must be 0 or greater.')]
        protected int $offset = 0,

        #[Assert\Range(notInRangeMessage: 'Limit must be between {{ min }} and {{ max }}.', min: 0, max: 100)]
        protected int $limit = 20
    )
    {
    }

    public static function fromRequest(Request $request, int $defaultOffset = 0, int $defaultLimit = 20): self
    {
        $offset = $request->query->getInt('offset', $defaultOffset);
        $limit = $request->query->getInt('limit', $defaultLimit);
        $limit = min($limit, static::PAGINATION_CAP_LIMIT);

        return new self($offset, $limit);
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
