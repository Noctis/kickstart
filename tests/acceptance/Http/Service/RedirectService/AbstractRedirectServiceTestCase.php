<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Service\RedirectService;

use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\RoutesCollection;
use Noctis\KickStart\Http\Service\RedirectService;
use Psr\Http\Message\ServerRequestInterface;
use Tests\Acceptance\AbstractContainerAwareTestCase;

abstract class AbstractRedirectServiceTestCase extends AbstractContainerAwareTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['basehref'] = '/';
        $this->setCurrentUri('https', 'localhost', '/');
        $this->setRoutes();
    }

    /**
     * @return array<int|string, RouteInterface>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    protected function getRedirectService(): RedirectService
    {
        return $this->container
            ->get(RedirectService::class);
    }

    private function setCurrentUri(string $schema, string $host, string $path): void
    {
        $request = $this->container
            ->get(ServerRequestInterface::class);
        $this->container
            ->set(
                ServerRequestInterface::class,
                $request->withUri(
                    $request->getUri()
                        ->withScheme($schema)
                        ->withHost($host)
                        ->withPath($path)
                )
            );
    }

    private function setRoutes(): void
    {
        $this->container
            ->set(
                '__routes',
                new RoutesCollection(
                    $this->getRoutes()
                )
            );
    }
}
