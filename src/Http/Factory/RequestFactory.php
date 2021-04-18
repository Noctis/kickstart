<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Factory;

use DI\Factory\RequestedEntry;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class RequestFactory implements RequestFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createFromGlobals(
        RequestedEntry $entry,
        ContainerInterface $c,
        array $vars = []
    ): ServerRequestInterface {
        $request = ServerRequestFactory::fromGlobals();

        foreach ($vars as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        return $request;
    }
}
