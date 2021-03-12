<?php declare(strict_types=1);
namespace Tests\Unit\Http\Routing\Handler\Definition\RouteHandlerDefinition;

use Noctis\KickStart\Http\Action\AbstractAction;
use Noctis\KickStart\Http\Middleware\AbstractMiddleware;
use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerDefinition;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerDefinition
 */
final class CreateFromValueTests extends TestCase
{
    public function test_it_is_correctly_created_from_an_action_class_name_in_array(): void
    {
        $value = [AbstractAction::class];

        $handlerDefinition = RouteHandlerDefinition::createFromValue($value);

        $this->assertSame(
            AbstractAction::class,
            $handlerDefinition->getActionClassName()
        );
        $this->assertSame(
            [],
            $handlerDefinition->getGuardNames()
        );
    }

    public function test_it_is_correctly_created_from_an_action_class_name_as_string(): void
    {
        $value = AbstractAction::class;

        $handlerDefinition = RouteHandlerDefinition::createFromValue($value);

        $this->assertSame(
            AbstractAction::class,
            $handlerDefinition->getActionClassName()
        );
        $this->assertSame(
            [],
            $handlerDefinition->getGuardNames()
        );
    }

    public function test_it_is_correctly_created_from_an_array_of_action_class_name_and_guard_classes_names(): void
    {
        $value = [AbstractAction::class, [
            AbstractMiddleware::class,
        ]];

        $handlerDefinition = RouteHandlerDefinition::createFromValue($value);

        $this->assertSame(
            AbstractAction::class,
            $handlerDefinition->getActionClassName()
        );
        $this->assertSame(
            [AbstractMiddleware::class],
            $handlerDefinition->getGuardNames()
        );
    }
}