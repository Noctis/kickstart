<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

use Laminas\Diactoros\Response\RedirectResponse;

interface RedirectServiceInterface
{
    /**
     * @param array<string, string|int> $queryParams
     */
    public function redirectToPath(string $path, array $queryParams = []): RedirectResponse;

    /**
     * @param array<string, string|int> $params
     */
    public function redirectToRoute(string $name, array $params = []): RedirectResponse;
}
