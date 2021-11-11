<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Factory\BaseHrefFactory;

use Noctis\KickStart\Http\Factory\BaseHrefFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * @covers BaseHrefFactory::createFromRequest()
 */
class BaseHrefFactoryTests extends TestCase
{
    use ProphecyTrait;

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['basehref'] = '/baz';
    }

    public function test_it_returns_correct_url_when_using_standard_port(): void
    {
        $request = $this->getRequest(
            $this->getUri('https', 'foo.bar')
        );
        $factory = new BaseHrefFactory();

        $baseHref = $factory->createFromRequest($request);

        $this->assertSame('https://foo.bar/baz/', $baseHref);
    }

    public function test_it_returns_correct_url_when_using_non_standard_port(): void
    {
        $request = $this->getRequest(
            $this->getUri('https', 'foo.bar', 8080)
        );
        $factory = new BaseHrefFactory();

        $baseHref = $factory->createFromRequest($request);

        $this->assertSame('https://foo.bar:8080/baz/', $baseHref);
    }

    /** @noinspection PhpUndefinedMethodInspection */
    private function getRequest(UriInterface $uri): ServerRequestInterface
    {
        /** @var ServerRequestInterface $request */
        $request = $this->prophesize(ServerRequestInterface::class);

        $request->getUri()
            ->willReturn($uri);

        return $request->reveal();
    }

    /** @noinspection PhpUndefinedMethodInspection */
    private function getUri(string $scheme, string $host, int $port = null): UriInterface
    {
        /** @var UriInterface $uri */
        $uri = $this->prophesize(UriInterface::class);

        $uri->getScheme()
            ->willReturn($scheme);

        $uri->getHost()
            ->willReturn($host);

        $uri->getPort()
            ->willReturn($port);

        return $uri->reveal();
    }
}
