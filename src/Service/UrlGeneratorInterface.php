<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

use Noctis\KickStart\ValueObject\GeneratedUriInterface;

interface UrlGeneratorInterface
{
    /**
     * @param array<string, int|string> $params
     */
    public function generate(string $template, array $params = []): GeneratedUriInterface;
}
