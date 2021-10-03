<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\RedirectResponse;

interface RedirectResponseFactoryInterface
{
    /**
     * @param array<string, string> $queryParams
     */
    public function toPath(string $path, array $queryParams = []): RedirectResponse;
}
