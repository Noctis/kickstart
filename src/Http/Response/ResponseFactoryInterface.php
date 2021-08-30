<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;

interface ResponseFactoryInterface
{
    /**
     * @deprecated Please use `Factory\HtmlResponseFactoryInterface::render()` method.
     * @param array<string, mixed> $params
     */
    public function htmlResponse(string $view, array $params = []): HtmlResponse;

    /**
     * @deprecated Please use `Factory\RedirectResponseFactoryInterface::toPath()` method.
     * @param array<string, string> $params
     */
    public function redirectionResponse(string $path, array $params = []): RedirectResponse;

    /**
     * @deprecated Please use `Factory\AttachmentResponseFactory` methods.
     */
    public function attachmentResponse(AttachmentInterface $attachment): AttachmentResponse;

    /**
     * @deprecated Please use `Factory\NotFoundResponseFactory::notFound()` method.
     */
    public function notFoundResponse(): EmptyResponse;
}
