<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\Definition;

use Noctis\KickStart\Http\Action\ActionInterface;
use Psr\Http\Server\MiddlewareInterface;

final class RouteHandlerInfo implements RouteHandlerInfoInterface
{
    /** @var class-string<ActionInterface> */
    private string $actionClassName;

    /** @var list<class-string<MiddlewareInterface>> */
    private array $middlewareNames;

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
                /** @var list<class-string<MiddlewareInterface>> $guardNames */
                $guardNames = $value[1];
            }

            return new self($actionClassName, $guardNames);
        }
    }

    /**
     * @param class-string<ActionInterface>           $actionClassName
     * @param list<class-string<MiddlewareInterface>> $guardNames
     */
    public function __construct(string $actionClassName, array $guardNames)
    {
        $this->actionClassName = $actionClassName;
        $this->middlewareNames = $guardNames;
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
    public function getMiddlewareNames(): array
    {
        return $this->middlewareNames;
    }
}
