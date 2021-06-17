<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Attachment;

use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class AttachmentFactory implements AttachmentFactoryInterface
{
    private StreamFactoryInterface $streamFactory;

    public function __construct(StreamFactoryInterface $streamFactory)
    {
        $this->streamFactory = $streamFactory;
    }

    public function createFromPath(
        string $path,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentInterface {
        return new Attachment(
            $this->streamFactory
                ->createStreamFromFile($path),
            $mimeType,
            $disposition
        );
    }

    public function createFromContent(
        string $content,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentInterface {
        return new Attachment(
            $this->streamFactory
                ->createStream($content),
            $mimeType,
            $disposition
        );
    }

    public function createFromResource(
        $resource,
        string $mimeType,
        DispositionInterface $disposition
    ): AttachmentInterface {
        return new Attachment(
            $this->streamFactory
                ->createStreamFromResource($resource),
            $mimeType,
            $disposition
        );
    }
}
