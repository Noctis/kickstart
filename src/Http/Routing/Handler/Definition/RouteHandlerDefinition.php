<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing\Handler\Definition;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;

final class RouteHandlerDefinition implements RouteHandlerDefinitionInterface
{
    /**
     * @var class-string<AbstractAction>
     * @psalm-suppress DeprecatedClass
     */
    private string $actionClassName;

    /** @var list<class-string<AbstractMiddleware>> */
    private array $guardNames;

    /**
     * @param class-string<AbstractAction>|array $value
     * @psalm-suppress DeprecatedClass
     */
    public static function createFromValue(string | array $value): self
    {
        if (is_string($value)) {
            return new self($value, []);
        } else {
            /**
             * @var class-string<AbstractAction> $actionClassName
             * @psalm-suppress DeprecatedClass
             */
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
     * @param class-string<AbstractAction>           $actionClassName
     * @param list<class-string<AbstractMiddleware>> $guardNames
     * @psalm-suppress DeprecatedClass
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
