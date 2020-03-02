<?php declare(strict_types=1);
namespace App\Provider;

interface ServicesProviderInterface
{
    /**
     * @return mixed[]
     */
    public function getServicesDefinitions(): array;
}