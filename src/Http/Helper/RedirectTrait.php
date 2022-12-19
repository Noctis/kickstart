<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Http\Service\RedirectServiceInterface;

trait RedirectTrait
{
    private RedirectServiceInterface $redirectService;

    /**
     * @param array<string, string> $queryParams
     */
    public function redirect(string $path, array $queryParams = []): RedirectResponse
    {
        return $this->redirectService
            ->redirectToPath($path, $queryParams);
    }

    /**
     * @param array<string, string|int> $params
     */
    public function redirectToRoute(string $name, array $params): RedirectResponse
    {
        return $this->redirectService
            ->redirectToRoute($name, $params);
    }
}
