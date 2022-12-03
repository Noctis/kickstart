<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Headers;

use Noctis\KickStart\Http\Helper\HeaderUtils;

use function Psl\Str\is_empty;
use function Psl\Str\replace_every;

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
        if (is_empty($fallbackFilename)) {
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
        return replace_every(
            $value,
            [
                '/'  => '',
                '\\' => '',
                '"'  => ''
            ]
        );
    }
}
