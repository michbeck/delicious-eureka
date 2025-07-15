<?php

namespace App\Http;

use App\Http\Request\CreateFoodRequest;
use App\Service\CollectionHandlerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddCollectionHandler
{
    public function __construct(
        protected ValidatorInterface         $validator,
        protected CollectionHandlerInterface $collectionHandler,
        protected SerializerInterface        $serializer
    ) {}

    public function handle(CreateFoodRequest $request): JsonResponse
    {
        // error handling could be handled better and centralized but's just an example
        $errors = $this->validator->validate($request);

        if ($errors->count()) {
            return new JsonResponse([
                'message' => 'Invalid request',
                'errors' => (string)$errors,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $foodItem = $this->collectionHandler->addItem($request);
            $data = $this->serializer->serialize(
                $foodItem,
                'json',
                ['groups' => ['api_response']]
            );

            return new JsonResponse($data, JsonResponse::HTTP_CREATED, [], true);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}