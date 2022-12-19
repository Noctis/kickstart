<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;

trait AttachmentTrait
{
    private AttachmentFactoryInterface $attachmentFactory;

    private AttachmentResponseFactoryInterface $attachmentResponseFactory;

    public function sendAttachment(AttachmentInterface $attachment): AttachmentResponse
    {
        return $this->responseWithAttachment($attachment);
    }

    public function sendFile(
        string $path,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        $attachment = $this->attachmentFactory
            ->createFromPath($path, $mimeType, $disposition);

        return $this->responseWithAttachment($attachment);
    }

    public function sendContent(
        string $content,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        $attachment = $this->attachmentFactory
            ->createFromContent($content, $mimeType, $disposition);

        return $this->responseWithAttachment($attachment);
    }

    /**
     * @param resource $resource
     */
    public function sendResource(
        $resource,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentResponse {
        $attachment = $this->attachmentFactory
            ->createFromResource($resource, $mimeType, $disposition);

        return $this->responseWithAttachment($attachment);
    }

    private function responseWithAttachment(AttachmentInterface $attachment): AttachmentResponse
    {
        return $this->getResponse()
            ->withBody(
                $attachment->getStream()
            )
            ->withHeader(
                'Content-Type',
                $attachment->getMimeType()
            )
            ->withHeader(
                'Content-Disposition',
                $attachment->getDisposition()
                    ->toString()
            );
    }

    private function getResponse(): AttachmentResponse
    {
        return $this->attachmentResponseFactory
            ->createResponse();
    }
}
