<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Middleware;

use DI\Container;
use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\Guard\GuardMiddlewareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequestHandlerStack implements RequestHandlerInterface
{
    private Container $container;

    /** @var class-string<AbstractAction> */
    private string $actionName;

    /** @var list<class-string<GuardMiddlewareInterface>>|array<empty, empty> */
    private array $guardsNames;

    /**
     * @param class-string<AbstractAction>                                     $actionName
     * @param list<class-string<GuardMiddlewareInterface>>|array<empty, empty> $guardsNames
     */
    public function __construct(Container $container, string $actionName, array $guardsNames)
    {
        $this->container = $container;
        $this->actionName = $actionName;
        $this->guardsNames = $guardsNames;
    }

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


    /**
     * @param class-string<GuardMiddlewareInterface> $name
     */
    public function getGuard(string $name): GuardMiddlewareInterface
    {
        return $this->container
            ->get($name);
    }
}