<?php

declare(strict_types=1);

namespace Noctis\KickStart\Console;

use Noctis\KickStart\BootableApplicationTrait;
use Noctis\KickStart\Console\Command\AbstractCommand;
use Noctis\KickStart\RunnableInterface;
use Psr\Container\ContainerInterface;
use ReflectionProperty;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

final class ConsoleApplication implements RunnableInterface
{
    use BootableApplicationTrait;

    /** @var list<class-string<AbstractCommand>> */
    private array $commandsClassesNames = [];

    /** @var callable | null */
    private $commandLoaderFactory = null;

    public function __construct(
        private readonly ContainerInterface        $container,
        private readonly SymfonyConsoleApplication $consoleApp
    ) {
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
