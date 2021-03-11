<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Psr\Log\LoggerInterface;

use Uicms\App\Service\Model;
use Uicms\App\Service\Import;

class ImportCommand extends Command
{
    protected static $defaultName = 'app:import';
    private $import;
    
    public function __construct(Import $import)
    {
        $this->import = $import;
        
        parent::__construct();
    }
    
    protected function configure()
    {
        $this->setDescription('Import data from files located in a specified directory');
        $this->addArgument('entity', InputArgument::REQUIRED, 'The name of the entity.');
        $this->addArgument('directory', InputArgument::REQUIRED, 'The path to the data directory.');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . '</comment>');
        
        $this->import($input, $output);
        
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
        
        return 0;
    }
    
    protected function import(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
                'CSV Import',
                '============',
                '',
            ]);
            
        $this->import->import($input->getArgument('entity'), $input->getArgument('directory'));
        
        #$output->writeln('Import data from ' . $input->getArgument('csvfile') . ' to ' . $input->getArgument('entityname'));
    }
}