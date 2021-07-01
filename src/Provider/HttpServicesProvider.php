<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\UriFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Noctis\KickStart\Http\Response\ResponseFactory;
use Noctis\KickStart\Http\Response\ResponseFactoryInterface;
use Noctis\KickStart\Http\Routing\Handler\ActionInvoker;
use Noctis\KickStart\Http\Routing\Handler\ActionInvokerInterface;
use Noctis\KickStart\Http\Routing\Handler\FoundHandler;
use Noctis\KickStart\Http\Routing\Handler\FoundHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandler;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\NotFoundHandler;
use Noctis\KickStart\Http\Routing\Handler\NotFoundHandlerInterface;
use Noctis\KickStart\Http\Routing\HttpInfoProvider;
use Noctis\KickStart\Http\Routing\HttpInfoProviderInterface;
use Noctis\KickStart\Http\Routing\Router;
use Noctis\KickStart\Http\Routing\RoutesLoader;
use Noctis\KickStart\Http\Routing\RoutesLoaderInterface;
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
            EmitterInterface::class => SapiEmitter::class,
            FoundHandlerInterface::class => FoundHandler::class,
            HttpInfoProviderInterface::class => HttpInfoProvider::class,
            MethodNotAllowedHandlerInterface::class => MethodNotAllowedHandler::class,
            NotFoundHandlerInterface::class => NotFoundHandler::class,
            RequestHandlerInterface::class => Router::class,
            ResponseFactoryInterface::class => ResponseFactory::class,
            RoutesLoaderInterface::class => RoutesLoader::class,
            RoutesParserInterface::class => RoutesParser::class,
            ServerRequestInterface::class => fn (): ServerRequestInterface => ServerRequestFactory::fromGlobals(),
            UriFactoryInterface::class => UriFactory::class,
        ];
    }
}
