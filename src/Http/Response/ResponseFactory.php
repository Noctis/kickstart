<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Psr\Http\Message\UriInterface;

/**
 * @deprecated since 2.3.0; will be removed in 3.0.0
 * @psalm-suppress DeprecatedInterface
 */
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

    /**
     * @inheritDoc
     */
    public function attachmentResponse(AttachmentInterface $attachment): AttachmentResponse
    {
        return new AttachmentResponse($attachment);
    }

    /**
     * @inheritDoc
     */
    public function notFoundResponse(): EmptyResponse
    {
        return new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND);
    }
}
