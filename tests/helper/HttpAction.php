<?php

declare(strict_types=1);

namespace Tests\Helper;

use Laminas\Diactoros\Response\TextResponse;
use Noctis\KickStart\Http\Action\ActionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class HttpAction implements ActionInterface
{
    public const DEFAULT_RESPONSE = 'action!';

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new TextResponse(self::DEFAULT_RESPONSE);
    }
}
