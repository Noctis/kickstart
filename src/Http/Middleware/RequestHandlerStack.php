<?php declare(strict_types=1);
namespace App\Http\Middleware;

use App\Http\Middleware\Guard\GuardMiddlewareInterface;
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

    public function __construct(Container $container, string $actionName, array $guardsNames)
    {
        $this->container = $container;
        $this->actionName = $actionName;
        $this->guardsNames = $guardsNames;
    }

    /**
     * @inheritDoc
     */
    public function handle(Request $request): Response
    {
        if (empty($this->guardsNames)) {
            $actionInvoker = new ActionInvoker($this->container, $this->actionName);

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