<?php

declare(strict_types=1);

namespace Noctis\KickStart\File;

use InvalidArgumentException;
use RuntimeException;
use SplFileInfo;

class File implements FileInterface
{
    private string $fileName;
    private string $filePath;
    private string $mimeContentType;

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
}
