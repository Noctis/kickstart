<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Helper;

use Symfony\Component\HttpFoundation\RedirectResponse;

trait HttpRedirectionTrait
{
    protected function redirect(string $url, array $params = []): RedirectResponse
    {
        $queryString = !empty($params)
            ? '?'. http_build_query($params)
            : '';

        return new RedirectResponse(getenv('basepath') . $url . $queryString);
    }
}