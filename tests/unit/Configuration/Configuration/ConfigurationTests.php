<?php

declare(strict_types=1);

namespace Tests\Unit\Configuration\Configuration;

use Noctis\KickStart\Configuration\Configuration;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ConfigurationTests extends TestCase
{
    public function test_it_is_empty_upon_initialization(): void
    {
        $this->assertNull(
            Configuration::get('foo')
        );
    }

    /**
     * @dataProvider mixedValuesProvider
     */
    public function test_it_returns_set_value(mixed $value): void
    {
        Configuration::set('option', $value);

        $this->assertSame(
            $value,
            Configuration::get('option')
        );
    }

    public function mixedValuesProvider(): array
    {
        return [
            '1. A `null` value' => [null],
            '2. An int value' => [13],
            '3. A float/double value' => [13.2],
            '4. A string value' => ['foobar'],
            '5a. A boolean `true` value' => [true],
            '5b. A boolean `false` value' => [false],
            '6. An object' => [new stdClass()]
        ];
    }

    public function test_it_tells_whether_it_has_given_value_or_not(): void
    {
        Configuration::set('foo', 'bar');

        $this->assertTrue(
            Configuration::has('foo')
        );
        $this->assertFalse(
            Configuration::has('bar')
        );
    }

    public function test_it_returns_default_value_if_it_does_not_have_the_given_option(): void
    {
        $this->assertSame(
            'bar',
            Configuration::get('foo', 'bar')
        );
    }

    /**
     * @dataProvider booleanValuesProvider
     */
    public function test_it_properly_evaluates_boolean_values(string $value, bool $expectedResult): void
    {
        $_ENV['foo'] = $value;

        $this->assertSame(
            $expectedResult,
            Configuration::get('foo')
        );
    }

    public function booleanValuesProvider(): array
    {
        return [
            '1. Boolean "true"' => [
                'value'          => 'true',
                'expectedResult' => true,
            ],
            '2. Boolean "false"' => [
                'value'          => 'false',
                'expectedResult' => false,
            ],
        ];
    }

    public function test_trailing_slash_is_removed_from_set_basehref(): void
    {
        $_ENV['basehref'] = '/foo/';

        $this->assertSame(
            '/foo',
            Configuration::getBaseHref()
        );
    }

    /**
     * @dataProvider environmentsProvider
     */
    public function test_is_production_returns_expected_result(string $env, bool $expectedResult): void
    {
        $_ENV['APP_ENV'] = $env;

        $this->assertSame(
            $expectedResult,
            Configuration::isProduction()
        );
    }

    public function environmentsProvider(): array
    {
        return [
            '1. Production environment' => [
                'env'            => 'prod',
                'expectedResult' => true,
            ],
            '2. Development environment' => [
                'env'            => 'dev',
                'expectedResult' => false,
            ],
        ];
    }
}
