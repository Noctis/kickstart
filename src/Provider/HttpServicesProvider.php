<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Http\Factory\RequestFactory;
use Noctis\KickStart\Http\Factory\SessionFactory;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandler;
use Noctis\KickStart\Http\Routing\Handler\MethodNotAllowedHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\RouteFoundHandler;
use Noctis\KickStart\Http\Routing\Handler\RouteFoundHandlerInterface;
use Noctis\KickStart\Http\Routing\Handler\RouteNotFoundHandler;
use Noctis\KickStart\Http\Routing\Handler\RouteNotFoundHandlerInterface;
use Noctis\KickStart\Http\Routing\HttpInfoProvider;
use Noctis\KickStart\Http\Routing\HttpInfoProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use function DI\factory;
use function DI\get;

final class HttpServicesProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            HttpInfoProviderInterface::class => HttpInfoProvider::class,
            MethodNotAllowedHandlerInterface::class => MethodNotAllowedHandler::class,
            RouteFoundHandlerInterface::class => RouteFoundHandler::class,
            RouteNotFoundHandlerInterface::class => RouteNotFoundHandler::class,
            Session::class => factory([SessionFactory::class, 'create']),
            Request::class => factory([RequestFactory::class, 'createFromGlobals'])
                ->parameter(
                    'vars',
                    get('request.vars')
                ),
        ];
    }
}