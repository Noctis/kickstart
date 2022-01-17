<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Response\Attachment\Attachment;

use Noctis\KickStart\Http\Response\Attachment\Attachment;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\StreamInterface;

final class AttachmentTests extends TestCase
{
    use ProphecyTrait;

    public function test_it_uses_given_arguments(): void
    {
        $stream = $this->getStream();
        $mimeType = 'foo/bar';
        $disposition = $this->getDisposition();

        $attachment = new Attachment($stream, $mimeType, $disposition);

        $this->assertSame(
            $stream,
            $attachment->getStream()
        );
        $this->assertSame(
            $mimeType,
            $attachment->getMimeType()
        );
        $this->assertSame(
            $disposition,
            $attachment->getDisposition()
        );
    }

    private function getStream(): StreamInterface
    {
        $stream = $this->prophesize(StreamInterface::class);

        return $stream->reveal();
    }

    private function getDisposition(): DispositionInterface
    {
        $disposition = $this->prophesize(DispositionInterface::class);

        return $disposition->reveal();
    }
}
