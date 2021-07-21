<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing\Route;

use Noctis\KickStart\Http\Routing\Route;
use PHPUnit\Framework\TestCase;

final class RouteTests extends TestCase
{
    /**
     * @covers \Noctis\KickStart\Http\Routing\Route
     */
    public function test_it_returns_correct_information(): void
    {
        $guards = [
            'App\Http\Middleware\Guard\FirstGuard',
            'App\Http\Middleware\Guard\SecondGuard'
        ];

        $route = new Route('GET', '/foo', 'App\Http\Action\FooAction', $guards);

        $this->assertSame(
            'GET',
            $route->getMethod()
        );
        $this->assertSame(
            '/foo',
            $route->getPath()
        );
        $this->assertSame(
            'App\Http\Action\FooAction',
            $route->getAction()
        );
        $this->assertSame(
            $guards,
            $route->getGuards()
        );
    }

    /**
     * @covers \Noctis\KickStart\Http\Routing\Route
     */
    public function test_it_can_be_created_with_no_guards(): void
    {
        $route = new Route('GET', '/foo', 'App\Http\Action\FooAction', []);

        $this->assertSame(
            'GET',
            $route->getMethod()
        );
        $this->assertSame(
            '/foo',
            $route->getPath()
        );
        $this->assertSame(
            'App\Http\Action\FooAction',
            $route->getAction()
        );
        $this->assertSame(
            [],
            $route->getGuards()
        );
    }
}