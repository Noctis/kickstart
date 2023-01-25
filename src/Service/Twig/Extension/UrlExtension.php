<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Twig\Extension;

use Noctis\KickStart\Service\PathGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class UrlExtension extends AbstractExtension
{
    public function __construct(
        private readonly PathGeneratorInterface $pathGenerator
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'path',
                $this->getPath(...)
            ),
        ];
    }

    /**
     * @param array<string, string|int> $params
     */
    private function getPath(string $routeName, array $params = []): string
    {
        return $this->pathGenerator
            ->toRoute($routeName, $params)
            ->toString();
    }
}
