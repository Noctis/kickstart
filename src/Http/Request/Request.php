<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements ServerRequestInterface
{
    protected ServerRequestInterface $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
        return $this->request
            ->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withProtocolVersion($version);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->request
            ->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
        return $this->request
            ->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        return $this->request
            ->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
        return $this->request
            ->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withHeader($name, $value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withAddedHeader($name, $value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withoutHeader($name);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->request
            ->getBody();
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withBody($body);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget()
    {
        return $this->request
            ->getRequestTarget();
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withRequestTarget($requestTarget);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->request
            ->getMethod();
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withMethod($method);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->request
            ->getUri();
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withUri($uri, $preserveHost);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getServerParams()
    {
        return $this->request
            ->getServerParams();
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams()
    {
        return $this->request
            ->getCookieParams();
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withCookieParams($cookies);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams()
    {
        return $this->request
            ->getQueryParams();
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withQueryParams($query);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles()
    {
        return $this->request
            ->getUploadedFiles();
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withUploadedFiles($uploadedFiles);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody()
    {
        return $this->request
            ->getParsedBody();
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withParsedBody($data);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function getAttributes()
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
    public function withAttribute($name, $value)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withAttribute($name, $value);

        return $clone;
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name)
    {
        $clone = clone $this;
        $clone->request = $clone->request
            ->withoutAttribute($name);

        return $clone;
    }
}
