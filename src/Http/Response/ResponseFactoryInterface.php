<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\File\FileInterface;
use Psr\Http\Message\UriInterface;

interface ResponseFactoryInterface
{
    /**
     * @param array<string, mixed> $params
     */
    public function htmlResponse(string $view, string $baseHref, array $params = []): HtmlResponse;

    /**
     * @param array<string, string> $params
     */
    public function redirectionResponse(UriInterface $uri, array $params = []): RedirectResponse;

    /**
     * @deprecated since version 2.1.0 (will be removed in 3.0.0)
     * @see ResponseFactoryInterface::attachmentResponse()
     * @psalm-suppress DeprecatedClass
     */
    public function fileResponse(FileInterface $file): FileResponse;

    public function attachmentResponse(AttachmentInterface $attachment): AttachmentResponse;

    public function notFoundResponse(): EmptyResponse;
}
