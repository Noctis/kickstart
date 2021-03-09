<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routing\Handler;

use Symfony\Component\HttpFoundation\Response;

interface RouteHandlerInterface
{
    public function handle(array $routeInfo): Response;
}