<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use Noctis\KickStart\Http\Service\AttachmentServiceInterface;

trait AttachmentTrait
{
    private AttachmentServiceInterface $attachmentService;

    public function sendAttachment(AttachmentInterface $attachment): AttachmentResponse
    {
        return $this->attachmentService
            ->sendAttachment($attachment);
    }

    public function sendFile(
        string $path,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        return $this->attachmentService
            ->sendFile($path, $mimeType, $disposition);
    }

    public function sendContent(
        string $content,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        return $this->attachmentService
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
        return $this->attachmentService
            ->sendResource($resource, $mimeType, $disposition);
    }
}
