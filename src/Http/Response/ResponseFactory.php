<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\File\FileInterface;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Psr\Http\Message\UriInterface;

final class ResponseFactory implements ResponseFactoryInterface
{
    private TemplateRendererInterface $templateRenderer;

    public function __construct(TemplateRendererInterface $templateRenderer)
    {
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * @inheritDoc
     */
    public function htmlResponse(string $view, string $baseHref, array $params = []): HtmlResponse
    {
        $params['basehref'] = $baseHref;
        $html = $this->templateRenderer
            ->render($view, $params);

        return new HtmlResponse($html);
    }

    /**
     * @inheritDoc
     */
    public function redirectionResponse(UriInterface $uri, array $params = []): RedirectResponse
    {
        if (!empty($params)) {
            $uri = $uri->withQuery(
                http_build_query($params)
            );
        }

        return new RedirectResponse((string)$uri);
    }

    public function fileResponse(FileInterface $file): FileResponse
    {
        return new FileResponse($file);
    }

    public function notFoundResponse(): EmptyResponse
    {
        return new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND);
    }
}
