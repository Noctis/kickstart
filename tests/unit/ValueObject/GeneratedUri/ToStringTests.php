<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObject\GeneratedUri;

use Noctis\KickStart\ValueObject\GeneratedUri;
use PHPUnit\Framework\TestCase;

final class ToStringTests extends TestCase
{
    public function test_it_returns_proper_string_when_query_string_is_empty(): void
    {
        $generatedUri = new GeneratedUri('/download');

        $this->assertSame(
            '/download',
            $generatedUri->toString()
        );
    }

    public function test_it_returns_proper_string_when_query_string_exists(): void
    {
        $generatedUri = new GeneratedUri('/download', ['first' => 'foo', 'second' => 'bar']);

        $this->assertSame(
            '/download?first=foo&second=bar',
            $generatedUri->toString()
        );
    }
}
