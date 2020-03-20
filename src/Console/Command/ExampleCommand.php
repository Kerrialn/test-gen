<?php


namespace App\Console\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;

class ExampleCommand extends Command {

    public const NAME = "example";
    private $symfonyStyle;

    public function __construct(SymfonyStyle $style)
    {
        $this->symfonyStyle = $style;
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->success("Hello World!");
        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Say hello to the world');
    }

}