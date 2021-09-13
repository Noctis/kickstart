<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\HtmlResponse;

interface HtmlResponseFactoryInterface
{
    public function render(string $view, array $params = []): HtmlResponse;
}
