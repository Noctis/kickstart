<?php

declare(strict_types=1);

namespace Noctis\KickStart\Console;

use Noctis\KickStart\ApplicationInterface;
use Noctis\KickStart\Console\Command\AbstractCommand;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

final class ConsoleApplication implements ApplicationInterface
{
    private SymfonyConsoleApplication $consoleApp;

    public function __construct(SymfonyConsoleApplication $consoleApp)
    {
        $this->consoleApp = $consoleApp;
    }

    public function run(): void
    {
        $this->consoleApp
            ->run();
    }

    /**
     * @param list<AbstractCommand> $commands
     */
    public function setCommands(array $commands): void
    {
        array_map(
            function (AbstractCommand $command): void {
                $this->consoleApp
                    ->add($command);
            },
            $commands
        );
    }
}
