<?php

declare(strict_types=1);

namespace Noctis\KickStart\ValueObject;

final class GeneratedUri implements GeneratedUriInterface
{
    /**
     * @param array<string, int|string> $queryString
     */
    public function __construct(
        private readonly string $path,
        private readonly array $queryString = []
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams(): array
    {
        return $this->queryString;
    }

    public function toString(): string
    {
        $queryString = count($this->queryString) > 0
            ? '?' . http_build_query($this->queryString)
            : '';

        return $this->path . $queryString;
    }
}
