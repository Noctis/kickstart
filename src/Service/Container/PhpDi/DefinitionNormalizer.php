<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container\PhpDi;

use Closure;
use Noctis\KickStart\Service\Container\Definition\ContainerDefinitionInterface;
use Noctis\KickStart\Service\Container\DefinitionNormalizerInterface;

use function Noctis\KickStart\Service\Container\autowire;
use function Noctis\KickStart\Service\Container\factory;
use function Psl\Class\exists;

final class DefinitionNormalizer implements DefinitionNormalizerInterface
{
    public function normalize(mixed $definition): mixed
    {
        if (is_string($definition) && exists($definition)) {
            $definition = autowire($definition);
        } elseif ($definition instanceof Closure) {
            $definition = factory($definition);
        }

        if ($definition instanceof ContainerDefinitionInterface) {
            return $definition();
        }

        return $definition;
    }
}
