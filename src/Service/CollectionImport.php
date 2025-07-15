<?php

namespace App\Service;

use App\Collection\FoodCollectionInterface;
use App\Exception\CollectionException;
use App\Exception\CollectionImportException;
use App\Http\Request\CreateFoodRequest;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CollectionImport implements CollectionImportInterface
{
    public function __construct(
        protected CollectionHandlerInterface $collectionHandler,
        protected JsonFileReaderInterface    $jsonFileReader,
        protected SerializerInterface        $serializer,
        protected ValidatorInterface         $validator
    )
    {
    }

    /**
     * @param string $filepath The path to the JSON file.
     *
     * @return array<string, FoodCollectionInterface> The collections after import.
     *
     * @throws CollectionException if the import fails or validation errors occur.
     */
    public function import(string $filepath): array
    {
        try {
            $requests = $this->buildCreateFoodRequests($filepath);
            $this->collectionHandler->addItems($requests);

            return $this->collectionHandler->getCollections();
        } catch (Exception $e) {
            throw new CollectionException(sprintf('Failed to import collection from "%s": %s', $filepath, $e->getMessage()), 0, $e);
        }
    }

    /**
     * @return list<CreateFoodRequest>
     *
     * @throws CollectionImportException
     */
    protected function buildCreateFoodRequests(string $filepath): array
    {
        $data = $this->jsonFileReader->read($filepath);
        $requests = $this->serializer->denormalize($data, CreateFoodRequest::class . '[]');
        $errors = $this->validator->validate($requests);

        if ($errors->count()) {
            throw new CollectionImportException((string)$errors);
        }

        return $requests;
    }
}
