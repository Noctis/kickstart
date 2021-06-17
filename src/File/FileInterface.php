<?php

declare(strict_types=1);

namespace Noctis\KickStart\File;

use Psr\Http\Message\StreamInterface;

/**
 * @deprecated since version 2.1.0 (will be removed in 3.0.0)
 * @see \Noctis\KickStart\Http\Response\Attachment\AttachmentInterface
 */
interface FileInterface
{
    public function getFileName(): string;

    public function getFilePath(): string;

    public function getMimeContentType(): string;

    public function getStream(): StreamInterface;
}
