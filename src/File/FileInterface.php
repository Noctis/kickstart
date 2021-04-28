<?php

declare(strict_types=1);

namespace Noctis\KickStart\File;

interface FileInterface
{
    public function getFileName(): string;

    public function getFilePath(): string;

    public function getMimeContentType(): string;
}
