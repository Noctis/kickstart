<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;
use Noctis\KickStart\Http\Response\Factory\NotFoundResponseFactoryInterface;

trait NotFoundTrait
{
    private NotFoundResponseFactoryInterface $notFoundResponseFactory;

    public function notFound(string $message = null): EmptyResponse | TextResponse
    {
        return $this->notFoundResponseFactory
            ->createResponse(reasonPhrase: (string)$message);
    }
}
