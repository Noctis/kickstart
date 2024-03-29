<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Action;

use Noctis\KickStart\Http\Response\Factory\NotFoundResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class NotFoundAction implements ActionInterface
{
    public function __construct(
        private readonly NotFoundResponseFactoryInterface $notFoundResponseFactory
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $this->notFoundResponseFactory
            ->createResponse();
    }
}
