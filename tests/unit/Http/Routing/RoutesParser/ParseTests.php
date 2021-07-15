<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing\RoutesParser;

use Noctis\KickStart\Http\Routing\RouteDefinitionInterface;
use Noctis\KickStart\Http\Routing\RoutesParser;
use PHPUnit\Framework\TestCase;

final class ParseTests extends TestCase
{
    /**
     * @covers \Noctis\KickStart\Http\Routing\RoutesParser::parse
     */
    public function test_it_creates_route_definition_from_given_route_with_no_guards(): void
    {
        $route = ['GET', '/foo', 'App\Http\Action\FooAction'];
        $parser = new RoutesParser();

        $definitions = $parser->parse([$route]);

        $this->assertSame(
            'GET',
            $definitions[0]->getMethod()
        );
        $this->assertSame(
            '/foo',
            $definitions[0]->getPath(),
        );
        $this->assertSame(
            'App\Http\Action\FooAction',
            $definitions[0]->getAction()
        );
        $this->assertSame(
            [],
            $definitions[0]->getGuards()
        );
    }

    /**
     * @covers \Noctis\KickStart\Http\Routing\RoutesParser::parse
     */
    public function test_it_creates_route_definition_from_given_route_with_guards(): void
    {
        $guards = [
            'App\Http\Middleware\FirstGuard',
            'App\Http\Middleware\SecondGuard',
        ];
        $route = ['GET', '/foo', 'App\Http\Action\FooAction', $guards];
        $parser = new RoutesParser();

        $definitions = $parser->parse([$route]);

        $this->assertSame(
            'GET',
            $definitions[0]->getMethod()
        );
        $this->assertSame(
            '/foo',
            $definitions[0]->getPath(),
        );
        $this->assertSame(
            'App\Http\Action\FooAction',
            $definitions[0]->getAction()
        );
        $this->assertSame(
            $guards,
            $definitions[0]->getGuards()
        );
    }

    /**
     * @covers \Noctis\KickStart\Http\Routing\RoutesParser::parse
     */
    public function test_it_returns_a_list_of_route_definitions_from_given_list_of_routes(): void
    {
        $routes = [
            ['GET', '/foo', 'App\Http\Action\FooAction'],
            ['POST', '/bar', 'App\Http\Action\BarAction']
        ];
        $parser = new RoutesParser();

        $definitions = $parser->parse($routes);

        $this->assertContainsOnlyInstancesOf(
            RouteDefinitionInterface::class,
            $definitions
        );
        $this->assertCount(
            count($routes),
            $definitions
        );
    }
}
