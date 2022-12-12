<?php

declare(strict_types=1);

namespace Tests\Acceptance\Service\PathGenerator;

use Noctis\KickStart\Http\Routing\Route;
use Noctis\KickStart\Http\Routing\RoutesCollection;
use Noctis\KickStart\Http\Routing\RoutesCollectionInterface;
use Noctis\KickStart\Service\PathGenerator;
use Noctis\KickStart\Service\UrlGenerator;
use Noctis\KickStart\Service\UrlGeneratorInterface;
use PHPUnit\Framework\TestCase;

final class GenerateTests extends TestCase
{
    public function test_it_returns_given_route_name_with_params_as_query_string_if_no_routes_were_provided(): void
    {
        $generator = new PathGenerator(
            $this->getUrlGenerator()
        );

        $path = $generator->generate('sign-in', ['foo' => 'bar']);

        $this->assertSame(
            'sign-in?foo=bar',
            $path->toString()
        );
    }

    public function test_it_returns_given_route_name_with_params_as_query_string_if_no_matching_route_exists(): void
    {
        $generator = new PathGenerator(
            $this->getUrlGenerator()
        );
        $generator->setRoutes(
            $this->getRoutes(['home' => '/'])
        );

        $path = $generator->generate('sign-in', ['foo' => 'bar']);

        $this->assertSame(
            'sign-in?foo=bar',
            $path->toString()
        );
    }

    public function test_it_returns_route_based_path_if_matching_route_exists(): void
    {
        $generator = new PathGenerator(
            $this->getUrlGenerator()
        );
        $generator->setRoutes(
            $this->getRoutes([
                'home' => '/',
                'sign-in' => '/sign-in/form',
            ])
        );

        $path = $generator->generate('sign-in', ['foo' => 'bar']);

        $this->assertSame(
            '/sign-in/form?foo=bar',
            $path->toString()
        );
    }

    /**
     * @param array<string, string> $basicRoutes
     */
    private function getRoutes(array $basicRoutes): RoutesCollectionInterface
    {
        $routes = [];
        foreach ($basicRoutes as $name => $path) {
            $routes[$name] = Route::get($path, 'FooAction');
        }

        return new RoutesCollection($routes);
    }

    private function getUrlGenerator(): UrlGeneratorInterface
    {
        return new UrlGenerator();
    }
}
