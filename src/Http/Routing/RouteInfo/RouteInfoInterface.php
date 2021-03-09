<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routing\RouteInfo;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\Guard\GuardMiddlewareInterface;

interface RouteInfoInterface
{
    /**
     * @return class-string<AbstractAction>
     */
    public function getActionClassName(): string;

    /**
     * @return array<string, string>
     */
    public function getRequestVars(): array;

    /**
     * @return list<class-string<GuardMiddlewareInterface>>
     */
    public function getGuardNames(): array;
}