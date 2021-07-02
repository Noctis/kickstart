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
use Noctis\KickStart\Http\Routing\RequestHandler;
use Noctis\KickStart\Http\Routing\Router;
use Noctis\KickStart\Http\Routing\RouterInterface;
use Noctis\KickStart\Http\Routing\RoutesLoader;
use Noctis\KickStart\Http\Routing\RoutesLoaderInterface;
use Noctis\KickStart\Http\Routing\RoutesParser;
use Noctis\KickStart\Http\Routing\RoutesParserInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function DI\autowire;
use function DI\get;

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
            RequestHandlerInterface::class => RequestHandler::class,
            ResponseFactoryInterface::class => ResponseFactory::class,
            RouterInterface::class => autowire(Router::class)
                ->constructorParameter(
                    'routes',
                    get('routes')
                ),
            RoutesLoaderInterface::class => RoutesLoader::class,
            RoutesParserInterface::class => RoutesParser::class,
            ServerRequestInterface::class => fn (): ServerRequestInterface => ServerRequestFactory::fromGlobals(),
            UriFactoryInterface::class => UriFactory::class,
        ];
    }
}
