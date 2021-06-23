<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Action;

use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Session\Container;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractAction
{
    protected ServerRequestInterface $request;
    protected Container $flashContainer;

    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory, ServerRequestInterface $request)
    {
        $this->responseFactory = $responseFactory;
        $this->request = $request;
        $this->flashContainer = new Container('flash');
    }

    protected function sendAttachment(AttachmentInterface $attachment): AttachmentResponse
    {
        return $this->responseFactory
            ->attachmentResponse($attachment);
    }

    protected function setFlashMessage(string $message): void
    {
        $this->flashContainer['message'] = $message;
    }

    protected function getFlashMessage(bool $persist = false): ?string
    {
        /** @var string|null $message */
        $message = $this->flashContainer['message'] ?? null;
        unset($this->flashContainer['message']);

        if ($message !== null && $persist) {
            $this->setFlashMessage($message);
        }

        return $message;
    }

    protected function notFound(): EmptyResponse
    {
        return $this->responseFactory
            ->notFoundResponse();
    }
}
