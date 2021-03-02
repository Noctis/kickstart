<?php declare(strict_types=1);
namespace Noctis\KickStart\Http;

use FastRoute\Dispatcher;
use Noctis\KickStart\AbstractApplication;
use Noctis\KickStart\Http\Routing\HttpRoutesProviderInterface;
use Noctis\KickStart\Http\Routing\Router;
use Noctis\KickStart\Provider\ConfigurationProvider;
use Noctis\KickStart\Provider\HttpServicesProvider;
use Noctis\KickStart\Provider\StandardServicesProvider;
use Noctis\KickStart\Provider\TwigServiceProvider;
use function FastRoute\simpleDispatcher;

abstract class AbstractWebApplication extends AbstractApplication
{
    private HttpRoutesProviderInterface $routesProvider;

    public function __construct(HttpRoutesProviderInterface $routesProvider)
    {
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
            new ConfigurationProvider(),
            new HttpServicesProvider(),
            new TwigServiceProvider($_ENV['basepath']),
            new StandardServicesProvider(),
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