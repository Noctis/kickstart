<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Attachment;

use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use Psr\Http\Message\StreamInterface;

final class Attachment implements AttachmentInterface
{
    private StreamInterface $stream;
    private string $mimeType;
    private DispositionInterface $disposition;

    public function __construct(StreamInterface $stream, string $mimeType, DispositionInterface $disposition)
    {
        $this->stream = $stream;
        $this->mimeType = $mimeType;
        $this->disposition = $disposition;
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
