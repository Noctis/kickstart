<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Attachment;

use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use Psr\Http\Message\StreamInterface;

interface AttachmentInterface
{
    public function getStream(): StreamInterface;

    public function getMimeType(): string;

    public function getDisposition(): DispositionInterface;
}
