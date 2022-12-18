<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

use Noctis\KickStart\ValueObject\GeneratedUri;
use Noctis\KickStart\ValueObject\GeneratedUriInterface;

final class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(string $template, array $params = []): GeneratedUriInterface
    {
        foreach ($params as $name => $value) {
            $replacementsMade = 0;
            $template = preg_replace("/\{$name\}|\{$name:.*?\}/i", (string)$value, $template, count: $replacementsMade);
            if ($replacementsMade > 0) {
                unset($params[$name]);
            }
        }

        return new GeneratedUri($template, $params);
    }
}
