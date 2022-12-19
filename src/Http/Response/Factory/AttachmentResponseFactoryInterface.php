<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Noctis\KickStart\Http\Response\AttachmentResponse;
use Psr\Http\Message\ResponseFactoryInterface;

interface AttachmentResponseFactoryInterface extends ResponseFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): AttachmentResponse;
}
