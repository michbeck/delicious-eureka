<?php

namespace App\Http\Resolver;

use App\Http\Request\CreateFoodRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class CreateFoodRequestResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== CreateFoodRequest::class) {
            return [];
        }

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $type = $request->attributes->get('type');

        yield new CreateFoodRequest(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            type: $type ?? null,
            quantity: $data['quantity'] ?? null,
            unit: $data['unit'] ?? null
        );
    }
}
