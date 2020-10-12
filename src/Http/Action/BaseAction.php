<?php declare(strict_types=1);
namespace App\Http\Action;

use App\Http\Helper\FlashMessageTrait;
use App\Http\Helper\HttpRedirectionTrait;
use App\Http\Helper\RenderTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

abstract class BaseAction
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

    protected function getBasePath(): string
    {
        return $this->request->getSchemeAndHttpHost() . $this->request->getBasePath();
    }

    protected function notFound(): Response
    {
        return new Response(
            '404, bro!',
            Response::HTTP_NOT_FOUND
        );
    }
}