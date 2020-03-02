<?php declare(strict_types=1);
namespace App\Http\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface GuardMiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}