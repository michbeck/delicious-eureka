<?php

namespace App\Controller;

use App\Http\AddCollectionHandler;
use App\Http\GetCollectionHandler;
use App\Http\Request\CreateFoodRequest;
use App\Http\Request\GetCollectionRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/v1/collections/{type}')]
class CollectionApiController extends AbstractController
{
    public function __construct(
        protected GetCollectionHandler $getCollectionHandler,
        protected AddCollectionHandler $addCollectionHandler,
    ) {}

    #[Route('', name: 'api_collections_get', methods: ['GET'])]
    public function list(GetCollectionRequest $request): JsonResponse
    {
        return $this->getCollectionHandler->handle($request);
    }

    #[Route('', name: 'api_collections_add', methods: ['POST'])]
    public function add(CreateFoodRequest $request): JsonResponse
    {
        return $this->addCollectionHandler->handle($request);
    }
}
