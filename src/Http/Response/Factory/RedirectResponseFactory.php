<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Configuration\Configuration;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;

use function Psl\Regex\matches;
use function Psl\Str\concat;

final class RedirectResponseFactory implements RedirectResponseFactoryInterface
{
    public function __construct(
        private readonly UriFactoryInterface    $uriFactory,
        private readonly ServerRequestInterface $request
    ) {
    }

    /**
     * @inheritDoc
     */
    public function toPath(string $path, array $queryParams = []): RedirectResponse
    {
        if (matches($path, '/:\/\//')) {
            $uri = $this->uriFactory
                ->createUri($path);
        } else {
            $uri = $this->request
                ->getUri()
                ->withPath(
                    concat(
                        Configuration::getBaseHref(),
                        '/',
                        $path
                    )
                );
        }

        $uri = $uri->withQuery(
            http_build_query($queryParams)
        );

        return new RedirectResponse((string)$uri);
    }
}
