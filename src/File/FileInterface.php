<?php

declare(strict_types=1);

namespace Noctis\KickStart\File;

use Psr\Http\Message\StreamInterface;

interface FileInterface
{
    public function getFileName(): string;

    public function getFilePath(): string;

    public function getMimeContentType(): string;

    public function getStream(): StreamInterface;
}
