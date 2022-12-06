<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container;

interface DefinitionNormalizerInterface
{
    public function normalize(mixed $definition): mixed;
}
