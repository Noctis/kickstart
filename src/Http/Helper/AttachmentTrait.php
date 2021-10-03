<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;

trait AttachmentTrait
{
    private AttachmentResponseFactoryInterface $attachmentResponseFactory;

    public function sendAttachment(AttachmentInterface $attachment): AttachmentResponse
    {
        return new AttachmentResponse($attachment);
    }

    public function sendFile(
        string $path,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        return $this->attachmentResponseFactory
            ->sendFile($path, $mimeType, $disposition);
    }

    public function sendContent(
        string $content,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        return $this->attachmentResponseFactory
            ->sendContent($content, $mimeType, $disposition);
    }

    /**
     * @param resource $resource
     */
    public function sendResource(
        $resource,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        return $this->sendResource($resource, $mimeType, $disposition);
    }
}
