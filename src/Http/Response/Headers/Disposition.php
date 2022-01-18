<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Headers;

use Symfony\Component\HttpFoundation\HeaderUtils;

final class Disposition implements DispositionInterface
{
    private string $disposition;

    public static function attachment(string $filename, string $fallbackFilename = null): self
    {
        return new self(HeaderUtils::DISPOSITION_ATTACHMENT, $filename, $fallbackFilename);
    }

    public static function inline(string $filename, string $fallbackFilename = null): self
    {
        return new self(HeaderUtils::DISPOSITION_INLINE, $filename, $fallbackFilename);
    }

    private function __construct(string $type, string $filename, string $fallbackFilename = null)
    {
        if ($fallbackFilename === null) {
            $fallbackFilename = $filename;
        }

        $this->disposition = HeaderUtils::makeDisposition(
            $type,
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
