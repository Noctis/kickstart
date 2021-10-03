<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Laminas\Diactoros\Response\HtmlResponse;
use Noctis\KickStart\Http\Response\Factory\HtmlResponseFactoryInterface;

trait RenderTrait
{
    private HtmlResponseFactoryInterface $htmlResponseFactory;

    /**
     * @param array<string, mixed> $params
     */
    public function render(string $view, array $params = []): HtmlResponse
    {
        return $this->htmlResponseFactory
            ->render($view, $params);
    }
}
