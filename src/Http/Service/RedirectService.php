<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Configuration\Configuration;
use Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface;
use Noctis\KickStart\Service\PathGeneratorInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

use function Psl\Regex\matches;
use function Psl\Str\concat;

final class RedirectService implements RedirectServiceInterface
{
    public function __construct(
        private readonly RedirectResponseFactoryInterface $redirectResponseFactory,
        private readonly ServerRequestInterface           $request,
        private readonly PathGeneratorInterface           $pathGenerator,
        private readonly UriFactoryInterface              $uriFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function redirectToPath(string $path, array $queryParams = []): RedirectResponse
    {
        $uri = $this->getUri($path)
            ->withQuery(
                http_build_query($queryParams)
            );

        return $this->redirectResponseFactory
            ->setUri($uri)
            ->createResponse();
    }

    /**
     * @inheritDoc
     */
    public function redirectToRoute(string $name, array $params = []): RedirectResponse
    {
        $generatedUri = $this->pathGenerator
            ->toRoute($name, $params);

        return $this->redirectToPath(
            $generatedUri->getPath(),
            $generatedUri->getQueryParams()
        );
    }

    private function getUri(string $path): UriInterface
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

        return $uri;
    }
}
