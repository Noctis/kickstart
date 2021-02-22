<?php declare(strict_types=1);
namespace Noctis\KickStart\Http;

use FastRoute\Dispatcher;
use Noctis\KickStart\AbstractApplication;
use Noctis\KickStart\Http\Routes\HttpRoutesProviderInterface;
use Noctis\KickStart\Provider\ConfigurationProvider as BaseConfigurationProvider;
use Noctis\KickStart\Provider\HttpServicesProvider;
use Noctis\KickStart\Provider\TwigServiceProvider;
use function FastRoute\simpleDispatcher;

abstract class AbstractHttpApplication extends AbstractApplication
{
    private string $env;
    private HttpRoutesProviderInterface $routesProvider;

    public function __construct(string $env, HttpRoutesProviderInterface $routesProvider)
    {
        $this->env = $env;
        $this->routesProvider = $routesProvider;
    }

    public function run(): void
    {
        $router = new Router(
            $this->getDispatcher(),
            $this->getContainerBuilder()
                ->build()
        );
        $router->route();
    }

    /**
     * @inheritDoc
     */
    protected function getServiceProviders(): array
    {
        return [
            new BaseConfigurationProvider(),
            new HttpServicesProvider(),
            new TwigServiceProvider($_ENV['BASEDIR'], $this->env)
        ];
    }

    private function getDispatcher(): Dispatcher
    {
        return simpleDispatcher(
            $this->routesProvider
                ->get()
        );
    }
}