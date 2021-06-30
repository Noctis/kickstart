<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use Noctis\KickStart\ApplicationInterface;
use Noctis\KickStart\Http\Routing\RouterInterface;

final class WebApplication implements ApplicationInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function run(): void
    {
        $this->router
            ->route();
    }

    /**
     * @param list<array> $routes
     */
    public function setRoutes(array $routes): void
    {
        $this->router
            ->setRoutes($routes);
    }
}
