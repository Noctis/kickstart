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

    /** @var array */
    private $vars = [];

    public function __construct(Container $container, string $actionName, array $vars)
    {
        $this->container = $container;
        $this->actionName = $actionName;
        $this->vars = $vars;
    }

    public function handle(Request $request): Response
    {
        return $this->container
            ->call([$this->actionName, 'execute'], $this->vars);
    }
}