<?php declare(strict_types=1);
namespace App\Provider;

use App\Http\Middleware\Guard\DummyGuard;

final class HttpMiddlewareProvider implements ServicesProviderInterface
{
    public function getServicesDefinitions(): array
    {
        return [
            DummyGuard::class => [
                null, [
                    'dummyParam' => getenv('dummy_param') === 'true',
                ]
            ],
        ];
    }
}