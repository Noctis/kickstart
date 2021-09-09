<?php

declare(strict_types=1);

namespace Noctis\KickStart\Console;

use DI\Container;
use Noctis\KickStart\AbstractApplication;
use Noctis\KickStart\Console\Command\AbstractCommand;
use Noctis\KickStart\Provider\ConfigurationProvider;
use ReflectionProperty;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

abstract class AbstractConsoleApplication extends AbstractApplication
{
    /** @var callable */
    private $commandLoaderFactory;

    /**
     * @param list<class-string<AbstractCommand>> $commandsClassesNames
     */
    public function __construct(array $commandsClassesNames)
    {
        parent::__construct();

        $this->commandLoaderFactory =
            function (Container $container) use ($commandsClassesNames): CommandLoaderInterface {
                return new ContainerCommandLoader(
                    $container,
                    $this->buildCommandMap($commandsClassesNames)
                );
            };
    }

    public function run(): void
    {
        $app = new SymfonyConsoleApplication();
        $app->setCommandLoader(
            $this->getCommandLoader()
        );
        $app->run();
    }

    public function setCommandLoaderFactory(callable $factory): void
    {
        $this->commandLoaderFactory = $factory;
    }

    private function getCommandLoader(): CommandLoaderInterface
    {
        /** @var CommandLoaderInterface */
        return call_user_func($this->commandLoaderFactory, $this->container);
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

    /**
     * @inheritDoc
     */
    protected function getServiceProviders(): array
    {
        return [
            new ConfigurationProvider(),
        ];
    }
}
