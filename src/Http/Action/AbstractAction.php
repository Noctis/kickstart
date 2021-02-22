<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Action;

use Noctis\KickStart\Http\Helper\FlashMessageTrait;
use Noctis\KickStart\Http\Helper\HttpRedirectionTrait;
use Noctis\KickStart\Http\Helper\RenderTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

abstract class AbstractAction
{
    use FlashMessageTrait, HttpRedirectionTrait, RenderTrait;

    protected Request $request;

    public function __construct(Twig $twig, Request $request)
    {
        $this->twig = $twig;

        $this->setRequest($request);
    }

    protected function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    protected function notFound(): Response
    {
        return new Response(
            '404, bro!',
            Response::HTTP_NOT_FOUND
        );
    }
}