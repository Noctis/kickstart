<?php

declare(strict_types=1);

namespace Noctis\KickStart\File;

use Laminas\Diactoros\StreamFactory;
use Psr\Http\Message\StreamInterface;

/**
 * @deprecated since version 2.1.0 (will be removed in 3.0.0)
 * @see \Noctis\KickStart\Http\Response\Attachment\Attachment
 * @psalm-suppress DeprecatedInterface
 * @psalm-suppress DeprecatedClass
 */
class InMemoryFile extends File
{
    private string $content;

    public function __construct(string $fileName, string $content, string $mimeContentType)
    {
        $this->filePath = '';
        $this->fileName = $fileName;
        $this->content = $content;
        $this->mimeContentType = $mimeContentType;
    }

    public function getStream(): StreamInterface
    {
        return (new StreamFactory())
            ->createStream($this->content);
    }
}
