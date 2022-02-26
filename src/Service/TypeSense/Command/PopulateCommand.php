<?php

namespace App\Service\TypeSense\Command;

use App\Service\TypeSense\TypeSense;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @AsCommand(
 * name="typesense:populate",
 * description="Populate the typesense schema collections",
 * hidden=false,
 * )
 */
class PopulateCommand extends Command
{
    private TypeSense $typeSense;

    public function __construct(TypeSense $typeSense)
    {
        parent::__construct();
        $this->typeSense = $typeSense;
    }

    protected function configure()
    {
        $this
            ->setName("typesense:populate")
            ->setDescription("Populate the typesense schema collections")
            ->setHidden(false)
        ;        
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->typeSense->populate();
        return 1;
    }
}
