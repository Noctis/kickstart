<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

interface ServicesProviderInterface
{
    /**
     * @return array<string, mixed>
     */
    public function getServicesDefinitions(): array;
}