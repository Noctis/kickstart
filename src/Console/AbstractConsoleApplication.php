<?php declare(strict_types=1);
namespace Noctis\KickStart\Console;

use Noctis\KickStart\AbstractApplication;
use Noctis\KickStart\Console\Command\AbstractCommand;
use Noctis\KickStart\Provider\ConfigurationProvider;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

abstract class AbstractConsoleApplication extends AbstractApplication
{
    private string $env;

    /** @var list<class-string<AbstractCommand>> */
    private array $commandsClassesNames;

    /**
     * @param list<class-string<AbstractCommand>> $commandsClassesNames
     */
    public function __construct(string $env, array $commandsClassesNames)
    {
        $this->env = $env;
        $this->commandsClassesNames = $commandsClassesNames;
    }

    public function run(): void
    {
        $app = new SymfonyConsoleApplication();
        $this->registerCommands($app);

        $app->run();
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

    private function registerCommands(SymfonyConsoleApplication $app): void
    {
        $container = $this->getContainerBuilder()
            ->build();

        foreach ($this->commandsClassesNames as $className) {
            $app->add(
                $container->get($className)
            );
        }
    }
}