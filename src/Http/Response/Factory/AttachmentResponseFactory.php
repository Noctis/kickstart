<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Factory;

use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use RuntimeException;

final class AttachmentResponseFactory implements AttachmentResponseFactoryInterface
{
    private ?AttachmentInterface $attachment;

    public function __construct()
    {
        $this->attachment = null;
    }

    public function setAttachment(AttachmentInterface $attachment): self
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function createResponse(int $code = 200, string $reasonPhrase = ''): AttachmentResponse
    {
        if ($this->attachment === null) {
            throw new RuntimeException(
                sprintf(
                    'Attachment not set. Did you forget to call `setAttachment()` on %s?',
                __CLASS__
                )
            );
        }

        return (new AttachmentResponse($this->attachment))
            ->withStatus($code, $reasonPhrase);
    }
}
