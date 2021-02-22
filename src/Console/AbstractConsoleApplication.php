<?php declare(strict_types=1);
namespace Noctis\KickStart\Console;

use Noctis\KickStart\AbstractApplication;
use Noctis\KickStart\Provider\ConfigurationProvider as BaseConfigurationProvider;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

abstract class AbstractConsoleApplication extends AbstractApplication
{
    private string $env;

    /** @var string[] */
    private array $commandsClassesNames;

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
            new BaseConfigurationProvider(),
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