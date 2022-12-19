<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

use Laminas\Diactoros\Response\HtmlResponse;
use Noctis\KickStart\Http\Factory\BaseHrefFactoryInterface;
use Noctis\KickStart\Http\Response\Factory\HtmlResponseFactoryInterface;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class RenderService implements RenderServiceInterface
{
    public function __construct(
        private readonly HtmlResponseFactoryInterface $htmlResponseFactory,
        private readonly ServerRequestInterface       $request,
        private readonly BaseHrefFactoryInterface     $baseHrefFactory,
        private readonly TemplateRendererInterface    $templateRenderer,
        private readonly StreamFactoryInterface       $streamFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function render(string $view, array $params = []): HtmlResponse
    {
        $html = $this->getHtml($view, $params);

        return $this->responseWithBody($html);
    }

    /**
     * @param array<string, mixed> $params
     */
    private function getHtml(string $view, array $params = []): string
    {
        $params['basehref'] = $this->baseHrefFactory
            ->createFromRequest($this->request);

        return $this->templateRenderer
            ->render($view, $params);
    }

    private function responseWithBody(string $html): HtmlResponse
    {
        return $this->htmlResponseFactory
            ->createResponse()
            ->withBody(
                $this->streamFactory
                    ->createStream($html)
            );
    }
}
