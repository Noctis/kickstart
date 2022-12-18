<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Request;

use Noctis\KickStart\Http\Request\Request;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface;

final class FromBodyTests extends TestCase
{
    use ProphecyTrait;

    public function test_it_returns_value_of_given_body_parameter(): void
    {
        $request = new Request(
            $this->getPsrRequest(['foo' => 'Foontastic', 'bar' => 'Barrrrrr!'])
        );
        $expectedResult = 'Foontastic';

        $result = $request->fromBody('foo');

        $this->assertSame($expectedResult, $result);
    }

    public function test_it_returns_null_by_default(): void
    {
        $request = new Request(
            $this->getPsrRequest(['foo' => 'Foontastic', 'bar' => 'Barrrrrr!'])
        );

        $result = $request->fromBody('baz');

        $this->assertNull($result);
    }

    public function test_it_returns_provided_default_value_if_one_was_provided(): void
    {
        $request = new Request(
            $this->getPsrRequest(['foo' => 'Foontastic', 'bar' => 'Barrrrrr!'])
        );
        $expectedResult = 'lolwut?';

        $result = $request->fromBody('baz', 'lolwut?');

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @param array<string, string|array|null> $parsedBody
     */
    private function getPsrRequest(array $parsedBody = []): ServerRequestInterface
    {
        /** @var ServerRequestInterface $request */
        $request = $this->prophesize(ServerRequestInterface::class);
        $request->getParsedBody()
            ->willReturn($parsedBody);

        return $request->reveal();
    }
}
