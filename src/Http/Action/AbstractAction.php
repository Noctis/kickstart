<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Action;

use Laminas\Diactoros\Response\EmptyResponse;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\ResponseFactoryInterface;

abstract class AbstractAction
{
    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    protected function sendAttachment(AttachmentInterface $attachment): AttachmentResponse
    {
        return $this->responseFactory
            ->attachmentResponse($attachment);
    }

    protected function notFound(): EmptyResponse
    {
        return $this->responseFactory
            ->notFoundResponse();
    }
}
