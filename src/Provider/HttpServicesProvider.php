<?php declare(strict_types=1);
namespace App\Provider;

use App\Http\Factory\RequestFactory;
use App\Http\Factory\SessionFactory;
use DI\Definition\Helper\FactoryDefinitionHelper;
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
        $requestFactory = factory([RequestFactory::class, 'createFromGlobals'])
            ->parameter(
                'vars',
                get('request.vars')
            );

        return [
            Session::class       => factory([SessionFactory::class, 'create']),
            Request::class       => $requestFactory,
            'App\Http\Request\*' => $requestFactory,
        ];
    }
}