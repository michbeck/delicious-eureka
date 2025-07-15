<?php

namespace App\Service;

use App\Exception\JsonFileReaderException;
use JsonException;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocatorInterface;

class JsonFileReader implements JsonFileReaderInterface
{
    public function __construct(
        protected FileLocatorInterface $fileLocator
    )
    {
    }

    /**
     * @param string $relativePath
     *
     * @return array<array<string, str|int>>
     *
     * @throws JsonFileReaderException
     */
    public function read(string $relativePath): array
    {
        try {
            $filePath = $this->fileLocator->locate($relativePath);
        } catch (FileLocatorFileNotFoundException $e) {
            throw new JsonFileReaderException(sprintf('Cannot find file: %s', $relativePath), 0, $e);
        }

        if (!is_readable($filePath)) {
            throw new JsonFileReaderException(sprintf('Cannot read file: %s', $relativePath));
        }

        $content = file_get_contents($filePath);

        if (trim($content) === '') {
            throw new JsonFileReaderException(sprintf('File is empty: %s', $relativePath));
        }

        try {
            $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

            if (!is_array($data)) {
                throw new JsonFileReaderException(sprintf('Cannot decode json: %s', $content));
            }

            return $data;
        } catch (JsonException $e) {
            throw new JsonFileReaderException(sprintf('Invalid JSON in file %s: %s', $relativePath, $e->getMessage()), 0, $e);
        }
    }
}
