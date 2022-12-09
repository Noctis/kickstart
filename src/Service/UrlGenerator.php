<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

final class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(string $template, array $params = []): string
    {
        foreach ($params as $name => $value) {
            $replacementsMade = 0;
            $template = preg_replace("/\{$name\}|\{$name:.*?\}/i", (string)$value, $template, count: $replacementsMade);
            if ($replacementsMade > 0) {
                unset($params[$name]);
            }
        }

        $queryString = count($params) > 0
            ? '?' . http_build_query($params)
            : '';

        return $template . $queryString;
    }
}
