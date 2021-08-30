<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Configuration\Configuration;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;

final class ResponseFactory implements ResponseFactoryInterface
{
    private ServerRequestInterface $request;
    private TemplateRendererInterface $templateRenderer;
    private UriFactoryInterface $uriFactory;

    public function __construct(
        ServerRequestInterface $request,
        TemplateRendererInterface $templateRenderer,
        UriFactoryInterface $uriFactory
    ) {
        $this->request = $request;
        $this->templateRenderer = $templateRenderer;
        $this->uriFactory = $uriFactory;
    }

    /**
     * @inheritDoc
     */
    public function htmlResponse(string $view, array $params = []): HtmlResponse
    {
        $params['basehref'] = $this->getBaseHref();
        $html = $this->templateRenderer
            ->render($view, $params);

        return new HtmlResponse($html);
    }

    /**
     * @inheritDoc
     */
    public function redirectionResponse(string $path, array $params = []): RedirectResponse
    {
        if (preg_match('/:\/\//', $path) === 1) {
            $path = $this->uriFactory
                ->createUri($path);
        } else {
            $path = $this->request
                ->getUri()
                ->withPath(
                    sprintf(
                        '%s/%s',
                        Configuration::getBaseHref(),
                        $path
                    )
                );
        }

        if (!empty($params)) {
            $path = $path->withQuery(
                http_build_query($params)
            );
        }

        return new RedirectResponse((string)$path);
    }

    public function attachmentResponse(AttachmentInterface $attachment): AttachmentResponse
    {
        return new AttachmentResponse($attachment);
    }

    public function notFoundResponse(): EmptyResponse
    {
        return new EmptyResponse(StatusCodeInterface::STATUS_NOT_FOUND);
    }

    private function getBaseHref(): string
    {
        $uri = $this->request
            ->getUri();

        $portPart = $uri->getPort() !== null
            ? ':' . (string)$uri->getPort()
            : '';

        return sprintf(
            '%s://%s/',
            $uri->getScheme(),
            $uri->getHost() . $portPart . Configuration::getBaseHref()
        );
    }
}
