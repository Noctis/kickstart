<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Laminas\Diactoros\UriFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\Session\Container as SessionContainer;
use Noctis\KickStart\Http\Factory\RequestFactory;
use Noctis\KickStart\Http\Response\ResponseFactory;
use Noctis\KickStart\Http\Response\ResponseFactoryInterface;
use Noctis\KickStart\Http\Routing\Handler\FoundHandler;
use Noctis\KickStart\Http\Routing\Handler\FoundHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandler;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\NotFoundHandler;
use Noctis\KickStart\Http\Routing\Handler\NotFoundHandlerInterface;
use Noctis\KickStart\Http\Routing\HttpInfoProvider;
use Noctis\KickStart\Http\Routing\HttpInfoProviderInterface;
use Noctis\KickStart\Http\Routing\RoutesLoader;
use Noctis\KickStart\Http\Routing\RoutesLoaderInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;

use function DI\autowire;
use function DI\factory;

final class HttpServicesProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            EmitterInterface::class => SapiEmitter::class,
            FoundHandlerInterface::class => FoundHandler::class,
            HttpInfoProviderInterface::class => HttpInfoProvider::class,
            MethodNotAllowedHandlerInterface::class => MethodNotAllowedHandler::class,
            NotFoundHandlerInterface::class => NotFoundHandler::class,
            ResponseFactoryInterface::class => ResponseFactory::class,
            RoutesLoaderInterface::class => RoutesLoader::class,
            ServerRequestInterface::class => factory([RequestFactory::class, 'createFromGlobals']),
            SessionContainer::class => autowire(SessionContainer::class)
                ->constructorParameter('name', 'flash'),
            UriFactoryInterface::class => UriFactory::class,
        ];
    }
}
