<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Fig\Http\Message\StatusCodeInterface;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Psr\Http\Message\ResponseFactoryInterface;

interface AttachmentResponseFactoryInterface extends ResponseFactoryInterface
{
    public function setAttachment(AttachmentInterface $attachment): self;

    /**
     * @inheritDoc
     */
    public function createResponse(
        int $code = StatusCodeInterface::STATUS_OK,
        string $reasonPhrase = ''
    ): AttachmentResponse;
}
