<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Factory;

use DI\Factory\RequestedEntry;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

interface RequestFactoryInterface
{
    /**
     * @param array<string, string> $vars
     */
    public function createFromGlobals(
        RequestedEntry $entry,
        ContainerInterface $c,
        array $vars = []
    ): ServerRequestInterface;
}
