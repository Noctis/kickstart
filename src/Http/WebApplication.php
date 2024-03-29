<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Noctis\KickStart\AbstractApplication;
use Noctis\KickStart\Http\Request\Request;
use Noctis\KickStart\Http\Routing\MiddlewareStack;
use Noctis\KickStart\Http\Routing\MiddlewareStackHandlerInterface;
use Noctis\KickStart\Http\Routing\RouteInterface;
use Noctis\KickStart\Http\Routing\Router\RouterInterface;
use Noctis\KickStart\Http\Service\RequestDecoratorInterface;
use Noctis\KickStart\Provider\HttpServicesProvider;
use Noctis\KickStart\Provider\ServicesProviderInterface;
use Noctis\KickStart\Provider\StandardServicesProvider;
use Noctis\KickStart\Provider\TwigServiceProvider;
use Noctis\KickStart\RunnableInterface;
use Noctis\KickStart\Service\Container\SettableContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class WebApplication extends AbstractApplication implements RunnableInterface
{
    /**
     * @inheritDoc
     * @psalm-return list<ServicesProviderInterface>
     */
    protected static function getObligatoryServiceProviders(): array
    {
        return [
            new HttpServicesProvider(),
        ];
    }

    public function __construct(
        SettableContainerInterface                       $container,
        RouterInterface                                  $router,
        private ServerRequestInterface                   $request,
        private readonly MiddlewareStackHandlerInterface $middlewareStackHandler,
        private readonly RequestDecoratorInterface       $requestDecorator,
        private readonly EmitterInterface                $responseEmitter
    ) {
        parent::__construct($container, $router);
    }

    public function run(): void
    {
        $response = $this->generateResponse();

        $this->responseEmitter
            ->emit($response);
    }

    private function generateResponse(): ResponseInterface
    {
        $route = $this->determineRoute();
        $this->prepareRequestHandler($route);
        $this->decorateRequest(
            $route->getAdditionalVars()
        );
        $this->createKickstartRequest($route);

        return $this->middlewareStackHandler
            ->handle($this->request);
    }

    private function determineRoute(): RouteInterface
    {
        return $this->router
            ->route($this->request);
    }

    private function prepareRequestHandler(RouteInterface $route): void
    {
        $this->middlewareStackHandler
            ->setMiddlewareStack(
                MiddlewareStack::createFromRoute($route)
            );
    }

    /**
     * @param array<string, string> $additionalVars
     */
    private function decorateRequest(array $additionalVars): void
    {
        $this->request = $this->requestDecorator
            ->withAttributes($this->request, $additionalVars);
    }

    private function createKickstartRequest(RouteInterface $route): void
    {
        if ($route->getCustomRequestClass() !== null) {
            $requestClass = $route->getCustomRequestClass();
        } else {
            $requestClass = Request::class;
        }
        /** @var class-string<Request> $requestClass */

        $this->reRegisterServerRequest();
        /** @var Request */
        $this->request = $this->container
            ->get($requestClass);
        $this->reRegisterServerRequest();
    }

    private function reRegisterServerRequest(): void
    {
        $this->container
            ->set(ServerRequestInterface::class, $this->request);
    }
}
