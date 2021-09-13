<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;

final class AttachmentResponseFactory implements AttachmentResponseFactoryInterface
{
    private AttachmentFactoryInterface $attachmentFactory;

    public function __construct(AttachmentFactoryInterface $attachmentFactory)
    {
        $this->attachmentFactory = $attachmentFactory;
    }

    public function sendFile(
        string $path,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        return new AttachmentResponse(
            $this->attachmentFactory
                ->createFromPath($path, $mimeType, $disposition)
        );
    }

    public function sendContent(
        string $content,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        return new AttachmentResponse(
            $this->attachmentFactory
                ->createFromContent($content, $mimeType, $disposition)
        );
    }

    /**
     * @inheritDoc
     */
    public function sendResource(
        $resource,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        return new AttachmentResponse(
            $this->attachmentFactory
                ->createFromResource($resource, $mimeType, $disposition)
        );
    }
}
