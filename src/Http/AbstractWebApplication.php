<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use FastRoute\Dispatcher;
use Noctis\KickStart\AbstractApplication;
use Noctis\KickStart\Http\Routing\Router;
use Noctis\KickStart\Http\Routing\RoutesLoaderInterface;
use Noctis\KickStart\Provider\ConfigurationProvider;
use Noctis\KickStart\Provider\HttpServicesProvider;
use Noctis\KickStart\Provider\StandardServicesProvider;
use Noctis\KickStart\Provider\TwigServiceProvider;

use function FastRoute\simpleDispatcher;

abstract class AbstractWebApplication extends AbstractApplication
{
    private array $routes;
    private RoutesLoaderInterface $routesLoader;

    public function __construct(array $routes)
    {
        parent::__construct();

        $this->routes = $routes;
        $this->routesLoader = $this->container
            ->get(RoutesLoaderInterface::class);
    }

    public function run(): void
    {
        $router = $this->container
            ->get(Router::class);
        $router->setDispatcher(
            $this->getDispatcher()
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
            $this->routesLoader
                ->load($this->routes)
        );
    }
}
