<?php declare(strict_types=1);
namespace App\Provider;

use App\Service\DummyService;
use App\Service\DummyServiceInterface;

final class DummyServicesProvider implements ServicesProviderInterface
{
    /**
     * @return mixed[]
     */
    public function getServicesDefinitions(): array
    {
        return [
            DummyServiceInterface::class => DummyService::class,
        ];
    }
}