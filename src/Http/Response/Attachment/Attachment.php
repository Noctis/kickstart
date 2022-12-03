<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Attachment;

use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use Psr\Http\Message\StreamInterface;

final class Attachment implements AttachmentInterface
{
    public function __construct(
        private readonly StreamInterface      $stream,
        private readonly string               $mimeType,
        private readonly DispositionInterface $disposition
    ) {
    }

    public function getStream(): StreamInterface
    {
        return $this->stream;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function getDisposition(): DispositionInterface
    {
        return $this->disposition;
    }
}
