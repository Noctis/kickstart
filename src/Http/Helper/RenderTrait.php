<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Laminas\Diactoros\Response\HtmlResponse;
use Noctis\KickStart\Http\Service\RenderServiceInterface;

trait RenderTrait
{
    private RenderServiceInterface $renderService;

    /**
     * @param array<string, mixed> $params
     */
    public function render(string $view, array $params = []): HtmlResponse
    {
        return $this->renderService
            ->render($view, $params);
    }
}
