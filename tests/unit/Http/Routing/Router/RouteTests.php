<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing\Router;

use FastRoute\Dispatcher;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Noctis\KickStart\Http\Routing\Handler\FoundHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\NotFoundHandlerInterface;
use Noctis\KickStart\Http\Routing\HttpInfoProviderInterface;
use Noctis\KickStart\Http\Routing\Router;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use RuntimeException;

/**
 * @covers \Noctis\KickStart\Http\Routing\Router::route
 */
final class RouteTests extends TestCase
{
    use ProphecyTrait;

    public function test_an_exception_is_thrown_if_no_dispatcher_has_been_set(): void
    {
        $this->expectException(RuntimeException::class);

        $router = new Router(
            $this->getHttpInfoProvider('GET', '/'),
            $this->getRouteFoundHandler(false),
            $this->getRouteNotFoundHandler(false),
            $this->getMethodNotAllowedHandler(false),
            $this->getResponseEmitter()
        );

        $router->route();
    }

    /**
     * @group doMe
     */
    public function test_route_found_handler_is_invoked_if_dispatcher_returns_found_http_code(): void
    {
        $router = new Router(
            $this->getHttpInfoProvider('GET', '/'),
            $this->getRouteFoundHandler(true),
            $this->getRouteNotFoundHandler(false),
            $this->getMethodNotAllowedHandler(false),
            $this->getResponseEmitter()
        );
        $router->setDispatcher(
            $this->getDispatcher(Dispatcher::FOUND)
        );

        $router->route();
    }

    public function test_route_not_found_handler_is_invoked_if_dispatcher_returns_not_found_code(): void
    {
        $router = new Router(
            $this->getHttpInfoProvider('GET', '/'),
            $this->getRouteFoundHandler(false),
            $this->getRouteNotFoundHandler(true),
            $this->getMethodNotAllowedHandler(false),
            $this->getResponseEmitter()
        );
        $router->setDispatcher(
            $this->getDispatcher(Dispatcher::NOT_FOUND)
        );

        $router->route();
    }

    public function test_method_not_allowed_handler_is_invoked_if_dispatcher_returns_method_not_allowed_code(): void
    {
        $router = new Router(
            $this->getHttpInfoProvider('GET', '/'),
            $this->getRouteFoundHandler(false),
            $this->getRouteNotFoundHandler(false),
            $this->getMethodNotAllowedHandler(true),
            $this->getResponseEmitter()
        );
        $router->setDispatcher(
            $this->getDispatcher(Dispatcher::METHOD_NOT_ALLOWED)
        );

        $router->route();
    }

    public function test_an_exception_is_thrown_if_dispatcher_returns_an_unexpected_code(): void
    {
        $this->expectException(RuntimeException::class);

        $router = new Router(
            $this->getHttpInfoProvider('GET', '/'),
            $this->getRouteFoundHandler(false),
            $this->getRouteNotFoundHandler(false),
            $this->getMethodNotAllowedHandler(false),
            $this->getResponseEmitter()
        );
        $router->setDispatcher(
            $this->getDispatcher(-1)
        );

        $router->route();
    }

    /**
     * @dataProvider partialHttpInfoProvider
     */
    public function test_an_exception_is_thrown_if_no_http_info_can_be_determined(
        ?string $httpMethod,
        ?string $uri
    ): void {
        $this->expectException(RuntimeException::class);

        $router = new Router(
            $this->getHttpInfoProvider($httpMethod, $uri),
            $this->getRouteFoundHandler(false),
            $this->getRouteNotFoundHandler(false),
            $this->getMethodNotAllowedHandler(false),
            $this->getResponseEmitter()
        );
        $router->setDispatcher(
            $this->getDispatcher(-1)
        );

        $router->route();
    }

    public function partialHttpInfoProvider(): array
    {
        return [
            '1. Only HTTP method' => ['GET', null],
            '2. Only URI'         => [null, '/'],
            '3. No info'          => [null, null],
        ];
    }

    /** @noinspection PhpUndefinedMethodInspection */
    private function getHttpInfoProvider(?string $httpMethod, ?string $uri): HttpInfoProviderInterface
    {
        /** @var HttpInfoProviderInterface $httpInfoProvider */
        $httpInfoProvider = $this->prophesize(HttpInfoProviderInterface::class);

        $httpInfoProvider->getMethod()
            ->willReturn($httpMethod);

        $httpInfoProvider->getUri()
            ->willReturn($uri);

        return $httpInfoProvider->reveal();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    private function getDispatcher(int $returnCode): Dispatcher
    {
        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->prophesize(Dispatcher::class);

        /** @noinspection PhpParamsInspection */
        $dispatcher->dispatch(Argument::cetera())
            ->willReturn([0 => $returnCode]);

        return $dispatcher->reveal();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    private function getRouteFoundHandler(bool $shouldBeCalled): FoundHandlerInterface
    {
        /** @var FoundHandlerInterface $handler */
        $handler = $this->prophesize(FoundHandlerInterface::class);

        if ($shouldBeCalled) {
            /** @noinspection PhpParamsInspection */
            $handler->handle(Argument::cetera())
                ->shouldBeCalledOnce();
        } else {
            /** @noinspection PhpParamsInspection */
            $handler->handle(Argument::cetera())
                ->shouldNotBeCalled();
        }

        return $handler->reveal();
    }

    private function getRouteNotFoundHandler(bool $shouldBeCalled): NotFoundHandlerInterface
    {
        /** @var NotFoundHandlerInterface $handler */
        $handler = $this->prophesize(NotFoundHandlerInterface::class);

        if ($shouldBeCalled) {
            /** @noinspection PhpParamsInspection */
            $handler->handle(Argument::cetera())
                ->shouldBeCalledOnce();
        } else {
            /** @noinspection PhpParamsInspection */
            $handler->handle(Argument::cetera())
                ->shouldNotBeCalled();
        }

        return $handler->reveal();
    }

    private function getMethodNotAllowedHandler(bool $shouldBeCalled): MethodNotAllowedHandlerInterface
    {
        /** @var MethodNotAllowedHandlerInterface $handler */
        $handler = $this->prophesize(MethodNotAllowedHandlerInterface::class);

        if ($shouldBeCalled) {
            /** @noinspection PhpParamsInspection */
            $handler->handle(Argument::cetera())
                ->shouldBeCalledOnce();
        } else {
            /** @noinspection PhpParamsInspection */
            $handler->handle(Argument::cetera())
                ->shouldNotBeCalled();
        }

        return $handler->reveal();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    private function getResponseEmitter(): EmitterInterface
    {
        /** @var EmitterInterface $emitter */
        $emitter = $this->prophesize(EmitterInterface::class);

        $emitter
            ->emit(
                Argument::any()
            )
            ->willReturn(true);

        return $emitter->reveal();
    }
}