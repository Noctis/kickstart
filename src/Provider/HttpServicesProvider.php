<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

use DI\Definition\Helper\FactoryDefinitionHelper;
use Noctis\KickStart\Http\Factory\RequestFactory;
use Noctis\KickStart\Http\Factory\SessionFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use function DI\factory;
use function DI\get;

final class HttpServicesProvider implements ServicesProviderInterface
{
    /**
     * @return FactoryDefinitionHelper[]
     */
    public function getServicesDefinitions(): array
    {
        return [
            Session::class => factory([SessionFactory::class, 'create']),
            Request::class => factory([RequestFactory::class, 'createFromGlobals'])
                ->parameter(
                    'vars',
                    get('request.vars')
                ),
        ];
    }
}