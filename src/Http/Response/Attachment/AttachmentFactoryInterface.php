<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Attachment;

use Noctis\KickStart\Http\Response\Headers\DispositionInterface;

interface AttachmentFactoryInterface
{
    public function createFromPath(
        string $path,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentInterface;

    public function createFromContent(
        string $content,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentInterface;

    /**
     * @param resource $resource
     */
    public function createFromResource(
        $resource,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentInterface;
}
