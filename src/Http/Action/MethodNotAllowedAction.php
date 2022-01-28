<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Action;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MethodNotAllowedAction implements ActionInterface
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new EmptyResponse(StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED);
    }
}
