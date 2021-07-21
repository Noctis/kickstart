<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing\RouteDefinition;

use Noctis\KickStart\Http\Routing\RouteDefinition;
use PHPUnit\Framework\TestCase;

final class RouteDefinitionTests extends TestCase
{
    /**
     * @covers \Noctis\KickStart\Http\Routing\RouteDefinition
     */
    public function test_it_returns_correct_information(): void
    {
        $guards = [
            'App\Http\Middleware\Guard\FirstGuard',
            'App\Http\Middleware\Guard\SecondGuard'
        ];

        $routeDefinition = new RouteDefinition('GET', '/foo', 'App\Http\Action\FooAction', $guards);

        $this->assertSame(
            'GET',
            $routeDefinition->getMethod()
        );
        $this->assertSame(
            '/foo',
            $routeDefinition->getPath()
        );
        $this->assertSame(
            'App\Http\Action\FooAction',
            $routeDefinition->getAction()
        );
        $this->assertSame(
            $guards,
            $routeDefinition->getGuards()
        );
    }

    /**
     * @covers \Noctis\KickStart\Http\Routing\RouteDefinition
     */
    public function test_it_can_be_created_with_no_guards(): void
    {
        $routeDefinition = new RouteDefinition('GET', '/foo', 'App\Http\Action\FooAction', []);

        $this->assertSame(
            'GET',
            $routeDefinition->getMethod()
        );
        $this->assertSame(
            '/foo',
            $routeDefinition->getPath()
        );
        $this->assertSame(
            'App\Http\Action\FooAction',
            $routeDefinition->getAction()
        );
        $this->assertSame(
            [],
            $routeDefinition->getGuards()
        );
    }
}
