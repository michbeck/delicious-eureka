<?php

namespace App\Http;

use App\Http\Request\GetCollectionRequest;
use App\Service\CollectionHandlerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetCollectionHandler
{
    public function __construct(
        protected ValidatorInterface         $validator,
        protected CollectionHandlerInterface $collectionHandler,
        protected SerializerInterface        $serializer
    )
    {
    }

    public function handle(GetCollectionRequest $request): JsonResponse
    {
        // error handling could be handled better and centralized but's just an example
        $errors = $this->validator->validate($request);
        if ($errors->count()) {
            return new JsonResponse([
                'error' => 'Invalid request',
                'message' => (string)$errors,
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $collection = $this->collectionHandler->loadCollection($request);
            $data = $this->serializer->serialize(
                $collection->list(),
                'json',
                ['groups' => ['api_response'], 'unit' => $request->getUnit()]
            );

            return new JsonResponse($data, JsonResponse::HTTP_OK, [], true);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}