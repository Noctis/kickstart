<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Routing\Handler\ActionInvoker;

use DI\Container;
use DI\ContainerBuilder;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\TextResponse;
use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Response\ResponseFactoryInterface;
use Noctis\KickStart\Http\Routing\Handler\ActionInvoker;
use Noctis\KickStart\Service\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Tests\Helper\MiddlewareGuard;

/**
 * @covers \Noctis\KickStart\Http\Routing\Handler\ActionInvoker::handle()
 */
final class HandleTests extends TestCase
{
    use ProphecyTrait;

    public function test_given_action_is_called_if_no_guards_were_given(): void
    {
        $request = $this->getRequest();
        $requestHandlerStack = $this->getRequestHandlerStack([]);

        $requestHandlerStack->handle($request);
    }

    /**
     * @group doMe
     *
     * This test actually does three things:
     *  1) it tests that if one of the middleware guards generated a response, the subsequent guards were not called,
     *  2) tests that the given middleware guards are executed in the expected order, and
     *  3) tests that the given action has not been called,
     */
    public function test_further_guards_are_not_executed_if_one_of_them_generated_their_own_response(): void
    {
        $request = $this->getRequest();
        $firstGuard = $this->getMiddlewareGuard()
            ->setResponse(
                $this->getResponse('first guard response')
            );
        $secondGuard = $this->getMiddlewareGuard()
            ->setResponse(
                $this->getResponse('second guard response')
            );
        $requestHandlerStack = $this->getRequestHandlerStack([$firstGuard, $secondGuard], false);

        $response = $requestHandlerStack->handle($request);

        $this->assertSame(
            'first guard response',
            $response->getBody()
                ->getContents()
        );
    }

    public function test_given_action_is_called_if_none_of_the_given_guards_generated_their_own_response(): void
    {
        $request = $this->getRequest();
        $firstGuard = $this->getMiddlewareGuard();
        $secondGuard = $this->getMiddlewareGuard();
        $requestHandlerStack = $this->getRequestHandlerStack([$firstGuard, $secondGuard]);

        $requestHandlerStack->handle($request);
    }

    /**
     * @param list<MiddlewareInterface> $guards
     */
    private function getRequestHandlerStack(array $guards, bool $actionShouldBeCalled = true): ActionInvoker
    {
        $actionInvoker = new ActionInvoker(
            $this->getContainer()
        );

        return $actionInvoker
            ->setAction(
                $this->getHttpAction($actionShouldBeCalled)
            )
            ->setGuards($guards);
    }

    private function getContainer(): Container
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            TemplateRendererInterface::class => $this->getTemplateRenderer(),
            ServerRequestInterface::class => $this->getRequest(),
        ]);

        return $containerBuilder->build();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    private function getHttpAction(bool $shouldBeCalled = true): AbstractAction
    {
        /** @var TestAction $action */
        $action = $this->prophesize(TestAction::class);

        if ($shouldBeCalled) {
            $action->execute()
                ->shouldBeCalledTimes(1);
        } else {
            $action->execute()
                ->shouldNotBeCalled();
        }

        return $action->reveal();
    }

    private function getMiddlewareGuard(): MiddlewareInterface
    {
        return new MiddlewareGuard(
            $this->getResponseFactory()
        );
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    private function getResponseFactory(): ResponseFactoryInterface
    {
        $responseFactory = $this->prophesize(ResponseFactoryInterface::class);

        return $responseFactory->reveal();
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    private function getRequest(): ServerRequestInterface
    {
        $request = $this->prophesize(ServerRequestInterface::class);

        return $request->reveal();
    }

    private function getResponse(string $content = ''): ResponseInterface
    {
        return new TextResponse($content);
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    private function getTemplateRenderer(): TemplateRendererInterface
    {
        $templateRenderer = $this->prophesize(TemplateRendererInterface::class);

        return $templateRenderer->reveal();
    }
}

abstract class TestAction extends AbstractAction
{
    abstract public function execute(): Response;
}