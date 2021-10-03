<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

abstract class AbstractRequest
{
    protected ServerRequestInterface $request;

    public function __construct(ServerRequestInterface $request)
    {
        $this->request = $request;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->request
            ->getAttribute($key);

        if ($value !== null) {
            return $value;
        }

        $parsedBody = $this->request
            ->getParsedBody();

        if (is_array($parsedBody) && array_key_exists($key, $parsedBody)) {
            return $parsedBody[$key];
        }

        $queryParams = $this->request
            ->getQueryParams();

        return $queryParams[$key] ?? $default;
    }

    public function getAttribute(string $name, mixed $default = null): mixed
    {
        return $this->request
            ->getAttribute($name, $default);
    }

    public function getParsedBody(): array | object | null
    {
        return $this->request
            ->getParsedBody();
    }

    /**
     * @return array<string, string>
     */
    public function getQueryParams(): array
    {
        /** @var array<string, string> */
        return $this->request
            ->getQueryParams();
    }

    /**
     * @return list<UploadedFileInterface>
     */
    public function getUploadedFiles(): array
    {
        /** @var list<UploadedFileInterface> */
        return $this->request
            ->getUploadedFiles();
    }
}
