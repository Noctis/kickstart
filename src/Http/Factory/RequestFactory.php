<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Factory;

use DI\Factory\RequestedEntry;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

final class RequestFactory implements RequestFactoryInterface
{
    /**
     * @param array<string, mixed>|array $vars
     */
    public function createFromGlobals(RequestedEntry $entry, ContainerInterface $c, array $vars = []): Request
    {
        $requestClassName = $entry->getName();
        /**
         * @psalm-suppress InvalidStringClass
         * @var Request $requestClassName
         */
        $request = $requestClassName::createFromGlobals();
        $request->setSession(
            $c->get(Session::class)
        );

        foreach ($vars as $name => $value) {
            $request->attributes
                ->set($name, $value);
        }

        return $request;
    }
}
