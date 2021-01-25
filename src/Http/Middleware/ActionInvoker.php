<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Middleware;

use DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ActionInvoker implements RequestHandlerInterface
{
    private Container $container;
    private string $actionName;

    public function __construct(Container $container, string $actionName)
    {
        $this->container = $container;
        $this->actionName = $actionName;
    }

    public function handle(Request $request): Response
    {
        return $this->container
            ->call([$this->actionName, 'execute']);
    }
}