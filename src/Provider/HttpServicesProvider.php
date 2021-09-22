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
use Noctis\KickStart\Http\Helper\RequestHelper;
use Noctis\KickStart\Http\Helper\RequestHelperInterface;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactory;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Factory\HtmlResponseFactory;
use Noctis\KickStart\Http\Response\Factory\HtmlResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Factory\NotFoundResponseFactory;
use Noctis\KickStart\Http\Response\Factory\NotFoundResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Factory\RedirectResponseFactory;
use Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface;
use Noctis\KickStart\Http\Routing\DispatcherFactory;
use Noctis\KickStart\Http\Routing\DispatcherFactoryInterface;
use Noctis\KickStart\Http\Routing\Handler\ActionInvoker;
use Noctis\KickStart\Http\Routing\Handler\ActionInvokerInterface;
use Noctis\KickStart\Http\Routing\RequestHandler;
use Noctis\KickStart\Http\Service\FlashMessageService;
use Noctis\KickStart\Http\Service\FlashMessageServiceInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function DI\autowire;

final class HttpServicesProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            ActionInvokerInterface::class => ActionInvoker::class,
            AttachmentResponseFactoryInterface::class => AttachmentResponseFactory::class,
            BaseHrefFactoryInterface::class => BaseHrefFactory::class,
            DispatcherFactoryInterface::class => DispatcherFactory::class,
            EmitterInterface::class => SapiEmitter::class,
            FlashMessageServiceInterface::class => FlashMessageService::class,
            HtmlResponseFactoryInterface::class => HtmlResponseFactory::class,
            ManagerInterface::class => SessionManager::class,
            NotFoundResponseFactoryInterface::class => NotFoundResponseFactory::class,
            RedirectResponseFactoryInterface::class => RedirectResponseFactory::class,
            RequestHandlerInterface::class => RequestHandler::class,
            RequestHelperInterface::class => RequestHelper::class,
            ServerRequestInterface::class => fn (): ServerRequestInterface => ServerRequestFactory::fromGlobals(),
            SessionContainer::class => autowire(SessionContainer::class)
                ->constructorParameter('name', 'flash'),
            UriFactoryInterface::class => UriFactory::class,
        ];
    }
}
