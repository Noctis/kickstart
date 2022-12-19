<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Noctis\KickStart\Http\Response\AttachmentResponse;

final class AttachmentResponseFactory implements AttachmentResponseFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): AttachmentResponse
    {
        return (new AttachmentResponse())
            ->withStatus($code, $reasonPhrase);
    }
}
