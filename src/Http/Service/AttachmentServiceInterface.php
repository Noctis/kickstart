<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;

interface AttachmentServiceInterface
{
    public function sendAttachment(AttachmentInterface $attachment): AttachmentResponse;

    public function sendFile(
        string $path,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse;

    public function sendContent(
        string $content,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse;

    /**
     * @param resource $resource
     */
    public function sendResource(
        $resource,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse;
}
