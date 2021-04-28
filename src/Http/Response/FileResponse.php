<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Stream;
use Noctis\KickStart\File\FileInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class FileResponse extends Response
{
    public function __construct(FileInterface $file, array $headers = [])
    {
        $stream = new Stream(
            $file->getFilePath(),
            'r'
        );

        $headers['Content-Encoding'] = 'none';
        $headers['Content-Type'] = $file->getMimeContentType();
        $headers['Content-Disposition'] = $this->getDisposition(
            $file->getFileName(),
            $file->getFileName(),
        );
        $headers['Content-Description'] = 'File Transfer';

        parent::__construct($stream, StatusCodeInterface::STATUS_OK, $headers);
    }

    private function getDisposition(string $fileName, string $fallbackFileName): string
    {
        return HeaderUtils::makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $this->escape($fileName),
            $this->escape($fallbackFileName),
        );
    }

    private function escape(string $value): string
    {
        return str_replace(
            ['/', '\\', '"'],
            '',
            $value
        );
    }
}
