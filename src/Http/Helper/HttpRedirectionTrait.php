<?php declare(strict_types=1);
namespace App\Http\Helper;

use Symfony\Component\HttpFoundation\RedirectResponse;

trait HttpRedirectionTrait
{
    use FlashMessageTrait;

    protected function redirectToProblem(string $message, string $returnUrl = null): RedirectResponse
    {
        $this->setFlashMessage($message, 'problem');
        $this->setFlashMessage($returnUrl, 'returnUrl');

        return $this->redirect('/problem');
    }

    protected function redirect(string $url, array $params = []): RedirectResponse
    {
        $queryString = !empty($params)
            ? '?'. http_build_query($params)
            : '';

        return new RedirectResponse($url . $queryString);
    }
}