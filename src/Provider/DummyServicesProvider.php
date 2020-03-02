<?php declare(strict_types=1);
namespace App\Provider;

final class DummyServicesProvider implements ServicesProviderInterface
{
    /**
     * @return mixed[]
     */
    public function getServicesDefinitions(): array
    {
        return [];
    }
}