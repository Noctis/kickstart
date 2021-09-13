<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Factory;

use Noctis\KickStart\Configuration\ConfigurationInterface;
use Psr\Http\Message\ServerRequestInterface;

final class BaseHrefFactory implements BaseHrefFactoryInterface
{
    private ConfigurationInterface $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function createFromRequest(ServerRequestInterface $request): string
    {
        $uri = $request->getUri();

        $portPart = $uri->getPort() !== null
            ? ':' . (string)$uri->getPort()
            : '';

        return sprintf(
            '%s://%s/',
            $uri->getScheme(),
            $uri->getHost() . $portPart . $this->configuration->getBaseHref()
        );
    }
}
