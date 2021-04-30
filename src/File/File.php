<?php

declare(strict_types=1);

namespace Noctis\KickStart\File;

use InvalidArgumentException;
use Laminas\Diactoros\StreamFactory;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use SplFileInfo;

class File implements FileInterface
{
    protected string $fileName;
    protected string $filePath;
    protected string $mimeContentType;

    public function __construct(string $filePath, string $mimeContentType)
    {
        $file = new SplFileInfo($filePath);
        if (!$file->isReadable()) {
            throw new RuntimeException(
                sprintf(
                    'File "%s" is not readable.',
                    $filePath
                )
            );
        }

        if ($file->isDir()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Given path ("%s") is a directory, not a file.',
                    $filePath
                )
            );
        }

        $this->fileName = $file->getFilename();
        $this->filePath = $file->getRealPath();
        $this->mimeContentType = $mimeContentType;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function getMimeContentType(): string
    {
        return $this->mimeContentType;
    }

    public function getStream(): StreamInterface
    {
        return (new StreamFactory())
            ->createStreamFromFile($this->filePath);
    }
}
