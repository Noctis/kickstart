<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;

interface AttachmentResponseFactoryInterface
{
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
