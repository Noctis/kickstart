<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Routing\Handler\Definition\RouteHandlerDefinition;

use Noctis\KickStart\Http\Action\ActionInterface;
use Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerInfo;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\MiddlewareInterface;

/**
 * @covers \Noctis\KickStart\Http\Routing\Handler\Definition\RouteHandlerInfo
 */
final class CreateFromValueTests extends TestCase
{
    public function test_it_is_correctly_created_from_an_action_class_name_in_array(): void
    {
        $value = [ActionInterface::class];

        $handlerInfo = RouteHandlerInfo::createFromValue($value);

        $this->assertSame(
            ActionInterface::class,
            $handlerInfo->getActionClassName()
        );
        $this->assertSame(
            [],
            $handlerInfo->getMiddlewareNames()
        );
    }

    public function test_it_is_correctly_created_from_an_action_class_name_as_string(): void
    {
        $value = ActionInterface::class;

        $handlerInfo = RouteHandlerInfo::createFromValue($value);

        $this->assertSame(
            ActionInterface::class,
            $handlerInfo->getActionClassName()
        );
        $this->assertSame(
            [],
            $handlerInfo->getMiddlewareNames()
        );
    }

    public function test_it_is_correctly_created_from_an_array_of_action_class_name_and_guard_classes_names(): void
    {
        $value = [ActionInterface::class, [
            MiddlewareInterface::class,
        ]];

        $handlerInfo = RouteHandlerInfo::createFromValue($value);

        $this->assertSame(
            ActionInterface::class,
            $handlerInfo->getActionClassName()
        );
        $this->assertSame(
            [MiddlewareInterface::class],
            $handlerInfo->getMiddlewareNames()
        );
    }
}