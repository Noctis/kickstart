<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Action;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Session\Container;
use Noctis\KickStart\Configuration\ConfigurationInterface;
use Noctis\KickStart\File\FileInterface;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\FileResponse;
use Noctis\KickStart\Http\Response\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * @deprecated since 2.3.0; will be removed in 3.0.0
 */
abstract class AbstractAction
{
    protected ServerRequestInterface $request;
    protected Container $flashContainer;

    /**
     * @psalm-suppress DeprecatedClass
     */
    private ResponseFactoryInterface $responseFactory;

    private UriFactoryInterface $uriFactory;
    private ConfigurationInterface $configuration;

    /** @psalm-suppress DeprecatedClass */
    public function __construct(
        ResponseFactoryInterface $responseFactory,
        UriFactoryInterface $uriFactory,
        ConfigurationInterface $configuration,
        ServerRequestInterface $request
    ) {
        $this->responseFactory = $responseFactory;
        $this->uriFactory = $uriFactory;
        $this->configuration = $configuration;
        $this->request = $request;
        $this->flashContainer = new Container('flash');
    }

    /**
     * @param array<string, mixed> $params
     */
    protected function render(string $view, array $params = []): HtmlResponse
    {
        $baseHref = $this->getBaseHref();

        /** @psalm-suppress DeprecatedMethod */
        return $this->responseFactory
            ->htmlResponse($view, $baseHref, $params);
    }

    /**
     * @param array<string, string> $params
     */
    protected function redirect(string $path, array $params = []): RedirectResponse
    {
        if (preg_match('/:\/\//', $path) === 1) {
            $uri = $this->uriFactory
                ->createUri($path);
        } else {
            $uri = $this->request
                ->getUri()
                ->withPath(
                    sprintf(
                        '%s/%s',
                        $this->configuration->getBaseHref(),
                        $path
                    )
                );
        }

        /** @psalm-suppress DeprecatedMethod */
        return $this->responseFactory
            ->redirectionResponse($uri, $params);
    }

    /**
     * @deprecated since version 2.1.0 (will be removed in 3.0.0)
     * @see AbstractAction::sendAttachment()
     * @psalm-suppress DeprecatedClass
     */
    protected function sendFile(FileInterface $file): FileResponse
    {
        /** @psalm-suppress DeprecatedMethod */
        return $this->responseFactory
            ->fileResponse($file);
    }

    protected function sendAttachment(AttachmentInterface $attachment): AttachmentResponse
    {
        /** @psalm-suppress DeprecatedMethod */
        return $this->responseFactory
            ->attachmentResponse($attachment);
    }

    protected function setFlashMessage(string $message): void
    {
        $this->flashContainer['message'] = $message;
    }

    protected function getFlashMessage(bool $persist = false): ?string
    {
        /** @var string|null $message */
        $message = $this->flashContainer['message'] ?? null;
        unset($this->flashContainer['message']);

        if ($message !== null && $persist) {
            $this->setFlashMessage($message);
        }

        return $message;
    }

    protected function notFound(): EmptyResponse
    {
        /** @psalm-suppress DeprecatedMethod */
        return $this->responseFactory
            ->notFoundResponse();
    }

    protected function getBaseHref(): string
    {
        $uri = $this->request
            ->getUri();

        $portPart = $uri->getPort() !== null
            ? ':' . (string)$uri->getPort()
            : '';

        return sprintf(
            '%s://%s/',
            $uri->getScheme(),
            $uri->getHost() . $portPart . $this->configuration->getBaseHref()
        );
    }
}
