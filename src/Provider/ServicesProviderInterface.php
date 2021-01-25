<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

interface ServicesProviderInterface
{
    /**
     * @return mixed[]
     */
    public function getServicesDefinitions(): array;
}