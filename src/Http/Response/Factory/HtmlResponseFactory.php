<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\HtmlResponse;
use Noctis\KickStart\Http\Factory\BaseHrefFactoryInterface;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Psr\Http\Message\ServerRequestInterface;

final class HtmlResponseFactory implements HtmlResponseFactoryInterface
{
    private TemplateRendererInterface $templateRenderer;
    private ServerRequestInterface $request;
    private BaseHrefFactoryInterface $baseHrefFactory;

    public function __construct(
        BaseHrefFactoryInterface $baseHrefFactory,
        ServerRequestInterface $request,
        TemplateRendererInterface $templateRenderer
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->request = $request;
        $this->baseHrefFactory = $baseHrefFactory;
    }

    public function render(string $view, array $params = []): HtmlResponse
    {
        $params['basehref'] = $this->baseHrefFactory
            ->createFromRequest($this->request);

        return new HtmlResponse(
            $this->templateRenderer
                ->render($view, $params)
        );
    }
}
