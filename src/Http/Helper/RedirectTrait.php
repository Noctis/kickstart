<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Laminas\Diactoros\Response\RedirectResponse;
use Noctis\KickStart\Http\Response\Factory\RedirectResponseFactoryInterface;
use Noctis\KickStart\Service\PathGeneratorInterface;

trait RedirectTrait
{
    private RedirectResponseFactoryInterface $redirectResponseFactory;
    private PathGeneratorInterface $pathGenerator;

    /**
     * @param array<string, string> $params
     */
    public function redirect(string $path, array $params = []): RedirectResponse
    {
        return $this->redirectResponseFactory
            ->toPath($path, $params);
    }

    public function redirectToRoute(string $name, array $params): RedirectResponse
    {
        $generatedUri = $this->pathGenerator
            ->toRoute($name, $params);

        return $this->redirect(
            $generatedUri->getPath(),
            $generatedUri->getQueryParams()
        );
    }
}
