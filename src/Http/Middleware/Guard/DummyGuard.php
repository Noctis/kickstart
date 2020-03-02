<?php declare(strict_types=1);
namespace App\Http\Middleware\Guard;

use App\Http\Helper\HttpRedirectionTrait;
use App\Http\Middleware\GuardMiddlewareInterface;
use App\Http\Middleware\RequestHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/** @psalm-suppress PropertyNotSetInConstructor */
final class DummyGuard implements GuardMiddlewareInterface
{
    use HttpRedirectionTrait;

    /** @var bool */
    private $dummyParam;

    public function __construct(bool $dummyParam)
    {
        $this->dummyParam = $dummyParam;
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        return $handler->handle($request);
    }
}