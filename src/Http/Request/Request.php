<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Request;

use Laminas\Session\ManagerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

class Request
{
    protected ServerRequestInterface $request;
    protected ManagerInterface $sessionManager;

    public function __construct(ServerRequestInterface $request, ManagerInterface $sessionManager)
    {
        $this->request = $request;
        $this->sessionManager = $sessionManager;
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

    public function getSessionID(): string
    {
        /** @var string */
        return $this->sessionManager
            ->getId();
    }
}
