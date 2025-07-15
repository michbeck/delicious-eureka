<?php

namespace App\Http\Resolver;

use App\Http\Request\CollectionCriteria;
use App\Http\Request\GetCollectionRequest;
use App\Http\Request\Pagination;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class GetCollectionRequestResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== GetCollectionRequest::class) {
            return [];
        }

        yield new GetCollectionRequest(
            type: $request->attributes->get('type'),
            unit: $request->query->get('unit'),
            criteria: CollectionCriteria::fromRequest($request),
            pagination: Pagination::fromRequest($request),
        );
    }
}
