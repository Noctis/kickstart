<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routing;

interface HttpInfoProviderInterface
{
    public function getMethod(): ?string;

    public function getUri(): ?string;

    public function getRawUri(): ?string;
}