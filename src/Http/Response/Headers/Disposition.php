<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Headers;

use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class Disposition implements DispositionInterface
{
    private string $disposition;

    public function __construct(string $filename, string $fallbackFilename = null)
    {
        if ($fallbackFilename === null) {
            $fallbackFilename = $filename;
        }

        $this->disposition = HeaderUtils::makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $this->escape($filename),
            $this->escape($fallbackFilename),
        );
    }

    public function toString(): string
    {
        return $this->disposition;
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
