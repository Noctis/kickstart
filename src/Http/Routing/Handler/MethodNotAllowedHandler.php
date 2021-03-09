<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routing\Handler;

use Symfony\Component\HttpFoundation\Response;

final class MethodNotAllowedHandler implements RouteHandlerInterface
{
    public function handle(array $routeInfo): Response
    {
        /** @var list<string> $allowedMethods */
        $allowedMethods = $routeInfo[1];

        return new Response(
            sprintf(
                'Allowed methods: %s.',
                implode(', ', $allowedMethods)
            ),
            Response::HTTP_METHOD_NOT_ALLOWED
        );
    }
}