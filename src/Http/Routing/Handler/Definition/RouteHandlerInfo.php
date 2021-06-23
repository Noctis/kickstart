<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\Definition;

use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;

final class RouteHandlerInfo implements RouteHandlerInfoInterface
{
    /** @var class-string<ActionInterface> */
    private string $actionClassName;

    /** @var list<class-string<AbstractMiddleware>> */
    private array $guardNames;

    /**
     * @param class-string<ActionInterface>|array $value
     */
    public static function createFromValue(string | array $value): self
    {
        if (is_string($value)) {
            return new self($value, []);
        } else {
            /** @var class-string<ActionInterface> $actionClassName */
            $actionClassName = $value[0];

            $guardNames = [];
            if (count($value) === 2) {
                /** @var list<class-string<AbstractMiddleware>> $guardNames */
                $guardNames = $value[1];
            }

            return new self($actionClassName, $guardNames);
        }
    }

    /**
     * @param class-string<ActionInterface>           $actionClassName
     * @param list<class-string<AbstractMiddleware>> $guardNames
     */
    public function __construct(string $actionClassName, array $guardNames)
    {
        $this->actionClassName = $actionClassName;
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
    public function getGuardNames(): array
    {
        return $this->guardNames;
    }
}
