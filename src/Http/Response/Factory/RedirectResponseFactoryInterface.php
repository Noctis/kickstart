<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\RedirectResponse;

interface RedirectResponseFactoryInterface
{
    public function toPath(string $path, array $queryParams = []): RedirectResponse;
}
