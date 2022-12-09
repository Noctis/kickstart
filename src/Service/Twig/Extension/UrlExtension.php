<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Twig\Extension;

use Noctis\KickStart\Service\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class UrlExtension extends AbstractExtension
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction(
                'path',
                $this->urlGenerator
                    ->generate(...)
            ),
        ];
    }
}
