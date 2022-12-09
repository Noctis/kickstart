<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

interface UrlGeneratorInterface
{
    /**
     * @param array<string, int|string> $params
     */
    public function generate(string $template, array $params = []): string;
}
