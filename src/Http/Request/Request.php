<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements ServerRequestInterface
{
    public function __construct(
        protected ServerRequestInterface $request
    ) {
    }

    public function fromQueryString(string $name, mixed $default = null): mixed
    {
        return $this->getQueryParams()[$name] ?? $default;
    }

    public function fromBody(string $name, mixed $default = null): mixed
    {
        return $this->getParsedBody()[$name] ?? $default;
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion(): string
    {
        return $this->request
            ->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withProtocolVersion($version);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders(): array
    {
        return $this->request
            ->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name): bool
    {
        return $this->request
            ->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name): array
    {
        return $this->request
            ->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name): string
    {
        return $this->request
            ->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withHeader($name, $value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withAddedHeader($name, $value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withoutHeader($name);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBody(): StreamInterface
    {
        return $this->request
            ->getBody();
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withBody($body);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget(): string
    {
        return $this->request
            ->getRequestTarget();
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withRequestTarget($requestTarget);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getMethod(): string
    {
        return $this->request
            ->getMethod();
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withMethod($method);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getUri(): UriInterface
    {
        return $this->request
            ->getUri();
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withUri($uri, $preserveHost);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getServerParams(): array
    {
        return $this->request
            ->getServerParams();
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams(): array
    {
        return $this->request
            ->getCookieParams();
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withCookieParams($cookies);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams(): array
    {
        return $this->request
            ->getQueryParams();
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withQueryParams($query);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles(): array
    {
        return $this->request
            ->getUploadedFiles();
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withUploadedFiles($uploadedFiles);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody(): null | array | object
    {
        return $this->request
            ->getParsedBody();
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withParsedBody($data);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes(): array
    {
        return $this->request
            ->getAttributes();
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->request
            ->getAttribute($name, $default);
    }

    /**
     * @inheritDoc
     */
    public function withAttribute($name, $value): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withAttribute($name, $value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name): static
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withoutAttribute($name);

        return $clone;
    }
}
