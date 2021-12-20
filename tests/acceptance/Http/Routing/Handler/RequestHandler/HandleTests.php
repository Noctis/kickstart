<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Routing\Handler\RequestHandler;

use DI\Container;
use DI\ContainerBuilder;
use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Routing\MiddlewareStack;
use Noctis\KickStart\Http\Routing\MiddlewareStackInterface;
use Noctis\KickStart\Http\Routing\RequestHandler;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tests\Helper\HttpAction;
use Tests\Helper\PassThroughMiddleware;
use Tests\Helper\TextResponseMiddleware;

/**
 * @covers \Noctis\KickStart\Http\Routing\RequestHandler::handle()
 */
final class HandleTests extends TestCase
{
    use ProphecyTrait;

    public function test_given_action_is_called_if_no_guards_were_given(): void
    {
        $requestHandler = $this->getRequestHandler([], HttpAction::class);

        $response = $requestHandler->handle(
            $this->getRequest()
        );

        $this->assertSame(
            HttpAction::DEFAULT_RESPONSE,
            $response->getBody()
                ->getContents()
        );
    }

    /**
     * This test actually does three things:
     *  1) it tests that if one of the middleware guards generated a response, the subsequent guards were not called,
     *  2) tests that the given middleware guards are executed in the expected order, and
     *  3) tests that the given action has not been called,
     */
    public function test_further_guards_are_not_executed_if_one_of_them_generated_their_own_response(): void
    {
        $middlewares = [
            TextResponseMiddleware::class,
            PassThroughMiddleware::class,
        ];
        /** @psalm-suppress InvalidArgument */
        $requestHandler = $this->getRequestHandler($middlewares, HttpAction::class);

        $response = $requestHandler->handle(
            $this->getRequest()
        );

        $this->assertSame(
            TextResponseMiddleware::DEFAULT_RESPONSE,
            $response->getBody()
                ->getContents()
        );
    }

    public function test_given_action_is_called_if_none_of_the_given_guards_generated_their_own_response(): void
    {
        $middlewares = [
            PassThroughMiddleware::class,
            PassThroughMiddleware::class,
        ];
        /** @psalm-suppress InvalidArgument */
        $requestHandler = $this->getRequestHandler($middlewares, HttpAction::class);

        $response = $requestHandler->handle(
            $this->getRequest()
        );

        $this->assertSame(
            HttpAction::DEFAULT_RESPONSE,
            $response->getBody()
                ->getContents()
        );
    }

    /**
     * @param list<class-string<MiddlewareInterface>> $guards
     * @param class-string<ActionInterface>           $actionClass
     */
    private function getRequestHandler(array $guards, string $actionClass): RequestHandler
    {
        $requestHandler = new RequestHandler(
            $this->getContainer()
        );

        $stack = $this->getMiddlewareStack($guards);
        $stack->addMiddleware($actionClass);
        $requestHandler->setMiddlewareStack($stack);

        return $requestHandler;
    }

    private function getContainer(): Container
    {
        $containerBuilder = new ContainerBuilder();

        return $containerBuilder->build();
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    private function getRequest(): ServerRequestInterface
    {
        $request = $this->prophesize(ServerRequestInterface::class);

        return $request->reveal();
    }

    /**
     * @param list<class-string<MiddlewareInterface>> $middlewares
     */
    private function getMiddlewareStack(array $middlewares): MiddlewareStackInterface
    {
        return new MiddlewareStack($middlewares);
    }
}
