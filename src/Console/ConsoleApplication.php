<?php

declare(strict_types=1);

namespace Noctis\KickStart\Console;

use Noctis\KickStart\ApplicationInterface;
use Noctis\KickStart\Console\Command\AbstractCommand;
use Psr\Container\ContainerInterface;
use ReflectionProperty;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

final class ConsoleApplication implements ApplicationInterface
{
    private ContainerInterface $container;
    private SymfonyConsoleApplication $consoleApp;

    /** @var list<class-string<AbstractCommand>> */
    private array $commandsClassesNames = [];

    /** @var callable | null */
    private $commandLoaderFactory = null;

    public function __construct(ContainerInterface $container, SymfonyConsoleApplication $consoleApp)
    {
        $this->container = $container;
        $this->consoleApp = $consoleApp;
    }

    public function run(): void
    {
        $this->consoleApp
            ->setCommandLoader(
                $this->getCommandLoader()
            );

        $this->consoleApp
            ->run();
    }

    /**
     * @param list<class-string<AbstractCommand>> $commandsClassesNames
     */
    public function setCommands(array $commandsClassesNames): void
    {
        $this->commandsClassesNames = $commandsClassesNames;
    }

    public function setCommandLoaderFactory(callable $factory): void
    {
        $this->commandLoaderFactory = $factory;
    }

    private function getCommandLoader(): CommandLoaderInterface
    {
        if ($this->commandLoaderFactory !== null) {
            /** @var CommandLoaderInterface */
            return call_user_func($this->commandLoaderFactory, $this->container);
        }

        return new ContainerCommandLoader(
            $this->container,
            $this->buildCommandMap($this->commandsClassesNames)
        );
    }

    /**
     * @param list<class-string<AbstractCommand>> $commandsClassesNames
     *
     * @return array<string, class-string<AbstractCommand>>
     */
    private function buildCommandMap(array $commandsClassesNames): array
    {
        $commandMap = [];
        foreach ($commandsClassesNames as $className) {
            $reflection = new ReflectionProperty($className, 'defaultName');
            $reflection->setAccessible(true);
            /** @var string $name */
            $name = $reflection->getValue();

            $commandMap[$name] = $className;
        }

        return $commandMap;
    }
}
