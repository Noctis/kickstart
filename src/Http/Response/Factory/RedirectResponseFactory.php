<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Configuration\Configuration;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;

final class RedirectResponseFactory implements RedirectResponseFactoryInterface
{
    private UriFactoryInterface $uriFactory;
    private ServerRequestInterface $request;

    public function __construct(
        UriFactoryInterface $uriFactory,
        ServerRequestInterface $request
    ) {
        $this->uriFactory = $uriFactory;
        $this->request = $request;
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
                        Configuration::getBaseHref(),
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
