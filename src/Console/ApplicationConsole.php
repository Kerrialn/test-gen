<?php
declare(strict_types=1);

namespace App\Console;

use App\Console\Command\ExampleCommand;
use Symfony\Component\Console\Application;

final class ApplicationConsole extends Application {
    public function __construct(ExampleCommand $exampleCommand)
    {
        $this->add($exampleCommand);
        parent::__construct('Example Console');
    }
}