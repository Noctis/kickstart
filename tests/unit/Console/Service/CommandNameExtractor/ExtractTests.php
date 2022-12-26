<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Service\CommandNameExtractor;

use Noctis\KickStart\Console\Service\CommandNameExtractor;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Attribute\AsCommand;

final class ExtractTests extends TestCase
{
    public function test_name_from_attribute_is_used_if_attribute_is_defined(): void
    {
        $expectedResult = 'attribute:named';
        $nameExtractor = new CommandNameExtractor();

        $result = $nameExtractor->extract(CommandWithNameAttribute::class);

        $this->assertSame($expectedResult, $result);
    }

    public function test_name_from_attribute_takes_precedence_over_name_in_property(): void
    {
        $expectedResult = 'attribute:first';
        $nameExtractor = new CommandNameExtractor();

        $result = $nameExtractor->extract(CommandWithNamePropertyAndNameAttribute::class);

        $this->assertSame($expectedResult, $result);
    }

    public function test_name_from_property_is_used_if_the_attribute_is_missing(): void
    {
        $expectedResult = 'property:named';
        $nameExtractor = new CommandNameExtractor();

        $result = $nameExtractor->extract(CommandWithNameProperty::class);

        $this->assertSame($expectedResult, $result);
    }
}

final class CommandWithNameProperty
{
    protected static $defaultName = 'property:named';
}

#[AsCommand(name: 'attribute:named')]
final class CommandWithNameAttribute
{
}

#[AsCommand(name: 'attribute:first')]
final class CommandWithNamePropertyAndNameAttribute
{
    protected static $defaultName = 'property:second';
}
