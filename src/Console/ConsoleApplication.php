<?php

declare(strict_types=1);

namespace Noctis\KickStart\Console;

use Noctis\KickStart\AbstractApplication;
use Noctis\KickStart\Console\Command\AbstractCommand;
use Noctis\KickStart\Console\Service\CommandNameExtractorInterface;
use Noctis\KickStart\Http\Routing\Router\RouterInterface;
use Noctis\KickStart\Provider\ConsoleServicesProvider;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\RunnableInterface;
use Noctis\KickStart\Service\Container\SettableContainerInterface;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

final class ConsoleApplication extends AbstractApplication implements RunnableInterface
{
    /** @var list<class-string<AbstractCommand>> */
    private array $commandsClassesNames = [];

    /** @var callable | null */
    private $commandLoaderFactory = null;

    private CommandNameExtractorInterface $commandNameExtractor;

    /**
     * @inheritDoc
     * @psalm-return list<ServicesProviderInterface>
     */
    protected static function getObligatoryServiceProviders(): array
    {
        return [
            new ConsoleServicesProvider(),
        ];
    }

    public function __construct(
        SettableContainerInterface                 $container,
        RouterInterface                            $router,
        private readonly SymfonyConsoleApplication $consoleApp
    ) {
        parent::__construct($container, $router);

        $this->commandNameExtractor = $this->container
            ->get(CommandNameExtractorInterface::class);
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
            $name = $this->commandNameExtractor
                ->extract($className);
            $commandMap[$name] = $className;
        }

        return $commandMap;
    }
}
