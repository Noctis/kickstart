<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Middleware;

use DI\Container;
use Noctis\KickStart\Http\Action\AbstractAction;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RequestHandlerStack implements RequestHandlerInterface
{
    private Container $container;
    private AbstractAction $action;

    /** @var list<AbstractMiddleware>|array<empty, empty> */
    private array $guards;

    /**
     * @param list<AbstractMiddleware>|array<empty, empty> $guards
     */
    public function __construct(Container $container, AbstractAction $action, array $guards)
    {
        $this->container = $container;
        $this->action = $action;
        $this->guards = $guards;
    }

    public function handle(Request $request): Response
    {
        if (empty($this->guards)) {
            /** @psalm-suppress InvalidArgument */
            return $this->container
                ->call([$this->action, 'execute']);
        }

        $guard = array_shift($this->guards);

        return $guard->process($request, $this);
    }
}