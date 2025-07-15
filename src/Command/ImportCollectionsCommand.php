<?php

namespace App\Command;

use App\Service\CollectionImport;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:import-data',
    description: 'Imports collections from a JSON file and displays the results.',
)]
class ImportCollectionsCommand extends Command
{
    const string FILEPATH_ARGUMENT_NAME = 'filepath';

    const string FILEPATH_ARGUMENT_DEFAULT = 'data/request.json';

    public function __construct(protected CollectionImport $collectionImport)
    {
        parent::__construct();
    }

    protected function configure()
    {
        $this->addArgument(
            static::FILEPATH_ARGUMENT_NAME,
            InputArgument::OPTIONAL,
            'Path to the JSON file',
            static::FILEPATH_ARGUMENT_DEFAULT
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filepath = $input->getArgument(static::FILEPATH_ARGUMENT_NAME);
        $collections = $this->collectionImport->import($filepath);

        foreach ($collections as $key => $collection) {
            if (!$collection->count()) {
                continue;
            }

            $output->writeln(
                sprintf("Imported %d %s items.", $collection->count(), $key)
            );
        }

        return Command::SUCCESS;
    }
}
