<?php

declare(strict_types=1);

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
    /** @var class-string<HttpRoutesProviderInterface> */
    private string $routesProviderClassName;

    /**
     * @param class-string<HttpRoutesProviderInterface> $routesProviderClassName
     */
    public function __construct(string $routesProviderClassName)
    {
        parent::__construct();

        $this->routesProviderClassName = $routesProviderClassName;
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
        /** @var HttpRoutesProviderInterface $routesProvider */
        $routesProvider = $this->container
            ->get($this->routesProviderClassName);

        return simpleDispatcher(
            $routesProvider->get()
        );
    }
}
