<?php declare(strict_types=1);
namespace App\Http\Middleware;

use DI\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequestHandlerStack implements RequestHandlerInterface
{
    /** @var Container */
    private $container;

    /** @var string */
    private $actionName;

    /** @var string[]|array */
    private $guardsNames = [];

    /** @var array */
    private $vars = [];

    public function __construct(Container $container, string $actionName, array $guardsNames, array $vars)
    {
        $this->container = $container;
        $this->actionName = $actionName;
        $this->guardsNames = $guardsNames;
        $this->vars = $vars;
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request): Response
    {
        if (empty($this->guardsNames)) {
            $actionInvoker = new ActionInvoker($this->container, $this->actionName, $this->vars);

            return $actionInvoker->handle($request);
        }

        $guard = $this->getGuard(
            array_shift($this->guardsNames)
        );

        return $guard->process($request, $this);
    }


    public function getGuard(string $name): GuardMiddlewareInterface
    {
        return $this->container
            ->get($name);
    }
}