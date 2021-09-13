<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\File\FileInterface;
use Psr\Http\Message\UriInterface;

/**
 * @deprecated since 2.3.0; will be removed in 3.0.0
 */
interface ResponseFactoryInterface
{
    /**
     * @deprecated Please use `Factory\HtmlResponseFactoryInterface::render()` method.
     * @param array<string, mixed> $params
     */
    public function htmlResponse(string $view, string $baseHref, array $params = []): HtmlResponse;

    /**
     * @deprecated Please use `Factory\RedirectResponseFactoryInterface::toPath()` method.
     * @param array<string, string> $params
     */
    public function redirectionResponse(UriInterface $uri, array $params = []): RedirectResponse;

    /**
     * @deprecated since version 2.1.0 (will be removed in 3.0.0)
     * @see Please use `Factory\AttachmentResponseFactory` methods.
     * @psalm-suppress DeprecatedClass
     */
    public function fileResponse(FileInterface $file): FileResponse;

    /**
     * @deprecated Please use `Factory\AttachmentResponseFactory` methods.
     */
    public function attachmentResponse(AttachmentInterface $attachment): AttachmentResponse;

    /**
     * @deprecated Please use `Factory\NotFoundResponseFactory::notFound()` method.
     */
    public function notFoundResponse(): EmptyResponse;
}
