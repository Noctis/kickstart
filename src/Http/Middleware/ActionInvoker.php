<?php declare(strict_types=1);
namespace App\Http\Middleware;

use DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class ActionInvoker implements RequestHandlerInterface
{
    /** @var Container */
    private $container;

    /** @var string */
    private $actionName;

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