<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Laminas\Diactoros\ServerRequestFactory as LaminasServerRequestFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class ServerRequestFactory implements ServerRequestFactoryInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createFromGlobals(): ServerRequestInterface
    {
        $request = LaminasServerRequestFactory::fromGlobals();

        /** @var array<string, string> $vars */
        $vars = $this->container
            ->get('request.vars');
        foreach ($vars as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        return $request;
    }
}
