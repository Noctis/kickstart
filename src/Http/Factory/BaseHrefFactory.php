<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Factory;

use Noctis\KickStart\Configuration\Configuration;
use Psr\Http\Message\ServerRequestInterface;

final class BaseHrefFactory implements BaseHrefFactoryInterface
{
    public function createFromRequest(ServerRequestInterface $request): string
    {
        $uri = $request->getUri();

        $portPart = $uri->getPort() !== null
            ? ':' . (string)$uri->getPort()
            : '';

        return sprintf(
            '%s://%s/',
            $uri->getScheme(),
            $uri->getHost() . $portPart . Configuration::getBaseHref()
        );
    }
}
