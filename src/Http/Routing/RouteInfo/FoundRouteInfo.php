<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routing\RouteInfo;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\Guard\GuardMiddlewareInterface;

final class FoundRouteInfo implements RouteInfoInterface
{
    /** @var class-string<AbstractAction> */
    private string $actionClassName;

    /** @var array<string, string> */
    private array $requestVars;

    /** @var list<class-string<GuardMiddlewareInterface>> */
    private array $guardNames;

    public static function createFromArray(array $routeInfo): self
    {
        /** @var array */
        $handler = $routeInfo[1];
        /** @var class-string<AbstractAction> */
        $actionClassName = $handler[0];
        $guardNames = [];
        if (count($handler) === 2) {
            /** @var list<class-string<GuardMiddlewareInterface>> */
            $guardNames = $handler[1];
        }

        /** @var array<string, string> $requestVars */
        $requestVars = $routeInfo[2];

        return new self($actionClassName, $requestVars, $guardNames);
    }

    /**
     * @param class-string<AbstractAction>                 $actionClassName
     * @param array<string, string>                        $requestVars
     * @param list<class-string<GuardMiddlewareInterface>> $guardNames
     */
    public function __construct(string $actionClassName, array $requestVars, array $guardNames)
    {
        $this->actionClassName = $actionClassName;
        $this->requestVars = $requestVars;
        $this->guardNames = $guardNames;
    }

    /**
     * @inheritDoc
     */
    public function getActionClassName(): string
    {
        return $this->actionClassName;
    }

    /**
     * @inheritDoc
     */
    public function getRequestVars(): array
    {
        return $this->requestVars;
    }

    /**
     * @inheritDoc
     */
    public function getGuardNames(): array
    {
        return $this->guardNames;
    }
}