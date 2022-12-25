<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

use Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;

final class AttachmentService implements AttachmentServiceInterface
{
    public function __construct(
        private readonly AttachmentFactoryInterface         $attachmentFactory,
        private readonly AttachmentResponseFactoryInterface $attachmentResponseFactory
    ) {
    }

    public function sendAttachment(AttachmentInterface $attachment): AttachmentResponse
    {
        return $this->responseWithAttachment($attachment);
    }

    public function sendFile(
        string $path,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        $attachment = $this->attachmentFactory
            ->createFromPath($path, $mimeType, $disposition);

        return $this->responseWithAttachment($attachment);
    }

    public function sendContent(
        string $content,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        $attachment = $this->attachmentFactory
            ->createFromContent($content, $mimeType, $disposition);

        return $this->responseWithAttachment($attachment);
    }

    /**
     * @inheritDoc
     */
    public function sendResource(
        $resource,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        $attachment = $this->attachmentFactory
            ->createFromResource($resource, $mimeType, $disposition);

        return $this->responseWithAttachment($attachment);
    }

    private function responseWithAttachment(AttachmentInterface $attachment): AttachmentResponse
    {
        return $this->attachmentResponseFactory
            ->setAttachment($attachment)
            ->createResponse();
    }
}
