<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service\Container;

use Psr\Container\ContainerInterface;

interface SettableContainerInterface extends ContainerInterface
{
    public function set(string $id, mixed $entry): void;
}
