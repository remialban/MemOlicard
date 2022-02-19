<?php

namespace App\Service\TypeSense\Command;

use App\Service\TypeSense\TypeSense;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'typesense:schema:update',
    description: 'Update the typesense schema collections',
    hidden: false,
)]
class SchemaCommand extends Command
{
    public function __construct(private TypeSense $typeSense)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->typeSense->updateSchema();
        return 1;
    }
}
