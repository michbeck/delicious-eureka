<?php

namespace App\Tests\Service;

use App\Exception\JsonFileReaderException;
use App\Service\JsonFileReader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;

class JsonFileReaderServiceTest extends TestCase
{
    protected JsonFileReader $reader;

    protected function setUp(): void
    {
        $path = \dirname(__DIR__) . '/fixtures';
        $fileLocator = new FileLocator($path);
        $this->reader = new JsonFileReader($fileLocator);
    }

    public function testReadSuccess(): void
    {
        $data = $this->reader->read('request.json');

        $this->assertIsArray($data);
        $this->assertCount(2, $data);
        $this->assertEquals('Carrot', $data[0]['name']);
        $this->assertEquals('vegetable', $data[0]['type']);
    }

    public function testReadThrowsOnFileNotFound(): void
    {
        $this->expectException(JsonFileReaderException::class);
        $this->expectExceptionMessage('Cannot find file');

        $this->reader->read('non-existent.json');
    }

    public function testReadThrowsOnInvalidJson(): void
    {
        $this->expectException(JsonFileReaderException::class);
        $this->expectExceptionMessage('Invalid JSON');

        $this->reader->read('invalid.json');
    }

    public function testReadThrowsOnEmptyFile(): void
    {
        $this->expectException(JsonFileReaderException::class);
        $this->expectExceptionMessage('is empty');

        $this->reader->read('empty.json');
    }
}
