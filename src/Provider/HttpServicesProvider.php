<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\UriFactory;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Laminas\Session\Container as SessionContainer;
use Laminas\Session\ManagerInterface;
use Laminas\Session\SessionManager;
use Noctis\KickStart\Http\Factory\BaseHrefFactory;
use Noctis\KickStart\Http\Factory\BaseHrefFactoryInterface;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactory;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Factory\HtmlResponseFactory;
use Noctis\KickStart\Http\Response\Factory\HtmlResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Factory\NotFoundResponseFactory;
use Noctis\KickStart\Http\Response\Factory\NotFoundResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Factory\RedirectResponseFactory;
use Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface;
use Noctis\KickStart\Http\Routing\MiddlewareStackHandlerInterface;
use Noctis\KickStart\Http\Routing\MiddlewareStackHandler;
use Noctis\KickStart\Http\Service\FlashMessageService;
use Noctis\KickStart\Http\Service\FlashMessageServiceInterface;
use Noctis\KickStart\Http\Service\RequestDecorator;
use Noctis\KickStart\Http\Service\RequestDecoratorInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;

use function DI\autowire;

final class HttpServicesProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            AttachmentResponseFactoryInterface::class => AttachmentResponseFactory::class,
            BaseHrefFactoryInterface::class => BaseHrefFactory::class,
            EmitterInterface::class => SapiEmitter::class,
            FlashMessageServiceInterface::class => FlashMessageService::class,
            HtmlResponseFactoryInterface::class => HtmlResponseFactory::class,
            ManagerInterface::class => SessionManager::class,
            MiddlewareStackHandlerInterface::class => MiddlewareStackHandler::class,
            NotFoundResponseFactoryInterface::class => NotFoundResponseFactory::class,
            RedirectResponseFactoryInterface::class => RedirectResponseFactory::class,
            RequestDecoratorInterface::class => RequestDecorator::class,
            ServerRequestInterface::class => fn (): ServerRequestInterface => ServerRequestFactory::fromGlobals(),
            SessionContainer::class => autowire(SessionContainer::class)
                ->constructorParameter('name', 'flash'),
            UriFactoryInterface::class => UriFactory::class,
        ];
    }
}
