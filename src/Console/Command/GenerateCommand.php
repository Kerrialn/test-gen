<?php


namespace App\Console\Command;


use App\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\PackageBuilder\Console\ShellCode;

class GenerateCommand  extends Command
{
    private const NAME = 'make';
    private SymfonyStyle $symfonyStyle;
    /**
     * @var Generator
     */
    private Generator $generator;

    public function __construct(SymfonyStyle $style, Generator $generator)
    {
        $this->symfonyStyle = $style;
        parent::__construct();
        $this->generator = $generator;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle->success('Generate Command');

        // generate tests
        $this->generator->generate();

        return ShellCode::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Generate phpUnit tests');
    }

}