<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

use Laminas\Diactoros\Response\HtmlResponse;

interface RenderServiceInterface
{
    /**
     * @param array<string, mixed> $params
     */
    public function render(string $view, array $params = []): HtmlResponse;
}
