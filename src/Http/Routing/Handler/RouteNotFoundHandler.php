<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routing\Handler;

use Symfony\Component\HttpFoundation\Response;

final class RouteNotFoundHandler implements RouteHandlerInterface
{
    public function handle(array $routeInfo): Response
    {
        return new Response(
            '404, bro!',
            Response::HTTP_NOT_FOUND
        );
    }
}