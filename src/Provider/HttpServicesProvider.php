<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\UriFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Noctis\KickStart\Http\Response\ResponseFactory;
use Noctis\KickStart\Http\Response\ResponseFactoryInterface;
use Noctis\KickStart\Http\Routing\DispatcherFactory;
use Noctis\KickStart\Http\Routing\DispatcherFactoryInterface;
use Noctis\KickStart\Http\Routing\Handler\ActionInvoker;
use Noctis\KickStart\Http\Routing\Handler\ActionInvokerInterface;
use Noctis\KickStart\Http\Routing\RequestHandler;
use Noctis\KickStart\Http\Routing\RoutesParser;
use Noctis\KickStart\Http\Routing\RoutesParserInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HttpServicesProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            ActionInvokerInterface::class => ActionInvoker::class,
            DispatcherFactoryInterface::class => DispatcherFactory::class,
            EmitterInterface::class => SapiEmitter::class,
            RequestHandlerInterface::class => RequestHandler::class,
            ResponseFactoryInterface::class => ResponseFactory::class,
            RoutesParserInterface::class => RoutesParser::class,
            ServerRequestInterface::class => fn (): ServerRequestInterface => ServerRequestFactory::fromGlobals(),
            UriFactoryInterface::class => UriFactory::class,
        ];
    }
}
