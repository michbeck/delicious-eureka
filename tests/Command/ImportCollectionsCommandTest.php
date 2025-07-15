<?php

namespace App\Tests\Command;

use App\Command\ImportCollectionsCommand;
use App\Service\CollectionImport;
use App\Service\JsonFileReaderInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCollectionsCommandTest extends KernelTestCase
{
    private Application $application;

    protected function setUp(): void
    {
        self::bootKernel();

        $command = $this->createCommand();
        $this->application = new Application(self::$kernel);
        $this->application->add($command);
    }

    public function testImport(): void
    {
        $command = $this->application->find('app:import-data');
        $tester = new CommandTester($command);
        $tester->execute([]);

        $output = $tester->getDisplay();
        $this->assertStringContainsString("Imported 1 fruit items.\nImported 2 vegetable items.\n", $output);
        $this->assertSame(0, $tester->getStatusCode());
    }

    protected function createCommand()
    {
        $data = [
            ['id' => 1, 'name' => uniqid('test_fruit_', true), 'type' => 'fruit', 'quantity' => 123, 'unit' => 'g'],
            ['id' => 2, 'name' => uniqid('test_vegetable_', true), 'type' => 'vegetable', 'quantity' => 456, 'unit' => 'g'],
            ['id' => 3, 'name' => uniqid('test_vegetable_', true), 'type' => 'vegetable', 'quantity' => 5, 'unit' => 'kg'],
        ];
        $mockReader = $this->createMock(JsonFileReaderInterface::class);
        $mockReader->method('read')->willReturn($data);

        // Override the service in the container
        self::getContainer()->set(JsonFileReaderInterface::class, $mockReader);

        $container = static::getContainer();
        $collectionImport = $container->get(CollectionImport::class);

        return new ImportCollectionsCommand($collectionImport);
    }
}

