<?php declare(strict_types=1);
namespace Tests\Acceptance\Http\Middleware\RequestHandlerStack;

use DI\Container;
use DI\ContainerBuilder;
use Noctis\KickStart\Configuration\ConfigurationInterface;
use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Noctis\KickStart\Http\Middleware\RequestHandlerInterface;
use Noctis\KickStart\Http\Middleware\RequestHandlerStack;
use Noctis\KickStart\Service\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @covers \Noctis\KickStart\Http\Middleware\RequestHandlerStack::handle()
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
     * This test actually does three things:
     *  1) it tests that if one of the middleware guards generated a response, the subsequent guards were not called,
     *  2) tests that given middleware guards are executed in the expected order, and
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
            $response->getContent()
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
     * @param list<AbstractMiddleware> $guards
     */
    private function getRequestHandlerStack(array $guards, bool $actionShouldBeCalled = true): RequestHandlerStack
    {
        return new RequestHandlerStack(
            $this->getContainer(),
            $this->getHttpAction($actionShouldBeCalled),
            $guards
        );
    }

    private function getContainer(): Container
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            ConfigurationInterface::class    => $this->getConfiguration(),
            TemplateRendererInterface::class => $this->getTemplateRenderer(),
            Request::class                   => $this->getRequest(),
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

    private function getMiddlewareGuard(): AbstractMiddleware
    {
        return new class extends AbstractMiddleware
        {
            private ?Response $response = null;

            public function setResponse(Response $response): self
            {
                $this->response = $response;

                return $this;
            }

            public function process(Request $request, RequestHandlerInterface $handler): Response
            {
                if ($this->response !== null) {
                    return $this->response;
                }

                return parent::process($request, $handler);
            }
        };
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    private function getRequest(): Request
    {
        $request = $this->prophesize(Request::class);

        return $request->reveal();
    }

    private function getResponse(string $content = ''): Response
    {
        return new Response($content);
    }

    /** @noinspection PhpIncompatibleReturnTypeInspection */
    private function getConfiguration(): ConfigurationInterface
    {
        $configuration = $this->prophesize(ConfigurationInterface::class);

        return $configuration->reveal();
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
