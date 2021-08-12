<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

class Request
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

        if (!array_key_exists($key, $queryParams)) {
            return $default;
        }

        return $queryParams[$key];
    }

    /**
     * @psalm-suppress MixedReturnTypeCoercion
     * @return list<UploadedFileInterface>
     */
    public function getFiles(): array
    {
        return $this->request
            ->getUploadedFiles();
    }

    public function getBaseHref(): string
    {
        return $this->request
            ->getRequestTarget();
    }
}
