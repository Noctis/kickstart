<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Configuration\ConfigurationInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;

final class RedirectResponseFactory implements RedirectResponseFactoryInterface
{
    private UriFactoryInterface $uriFactory;
    private ServerRequestInterface $request;
    private ConfigurationInterface $configuration;

    public function __construct(
        UriFactoryInterface    $uriFactory,
        ServerRequestInterface $request,
        ConfigurationInterface $configuration
    ) {
        $this->uriFactory = $uriFactory;
        $this->request = $request;
        $this->configuration = $configuration;
    }

    public function toPath(string $path, array $queryParams = []): RedirectResponse
    {
        if (preg_match('/:\/\//', $path) === 1) {
            $uri = $this->uriFactory
                ->createUri($path);
        } else {
            $uri = $this->request
                ->getUri()
                ->withPath(
                    sprintf(
                        '%s/%s',
                        $this->configuration
                            ->getBaseHref(),
                        $path
                    )
                );
        }

        if (!empty($queryParams)) {
            $uri = $uri->withQuery(
                http_build_query($queryParams)
            );
        }

        return new RedirectResponse((string)$uri);
    }
}
