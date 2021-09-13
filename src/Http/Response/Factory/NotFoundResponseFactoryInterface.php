<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;

interface NotFoundResponseFactoryInterface
{
    public function notFound(string $message = null): EmptyResponse | TextResponse;
}
