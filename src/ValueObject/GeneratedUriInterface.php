<?php

declare(strict_types=1);

namespace Noctis\KickStart\ValueObject;

interface GeneratedUriInterface
{
    public function getPath(): string;

    /**
     * @return array<string, int|string>
     */
    public function getQueryParams(): array;

    public function toString(): string;
}
