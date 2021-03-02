<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Helper;

use Noctis\KickStart\Configuration\ConfigurationInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

trait HttpRedirectionTrait
{
    protected ConfigurationInterface $configuration;

    protected function redirect(string $url, array $params = []): RedirectResponse
    {
        $baseHref = $this->configuration
            ->get('basehref');

        $queryString = !empty($params)
            ? '?'. http_build_query($params)
            : '';

        return new RedirectResponse($baseHref . $url . $queryString);
    }
}