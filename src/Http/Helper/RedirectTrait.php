<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface;

trait RedirectTrait
{
    private RedirectResponseFactoryInterface $redirectResponseFactory;

    /**
     * @param array<string, string> $params
     */
    public function redirect(string $path, array $params = []): RedirectResponse
    {
        return $this->redirectResponseFactory
            ->toPath($path, $params);
    }
}
