<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use Noctis\KickStart\AbstractApplication;
use Noctis\KickStart\Http\Routing\RouterInterface;

abstract class AbstractWebApplication extends AbstractApplication
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param list<array> $routes
     */
    public function setRoutes(array $routes): void
    {
        $this->router
            ->setRoutes($routes);
    }

    public function run(): void
    {
        $this->router
            ->route();
    }
}
