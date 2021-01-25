<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Middleware\Guard;

use Noctis\KickStart\Http\Middleware\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface GuardMiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}