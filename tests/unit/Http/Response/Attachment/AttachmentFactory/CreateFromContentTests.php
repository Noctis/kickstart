<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Response\Attachment\AttachmentFactory;

use Noctis\KickStart\Http\Response\Attachment\AttachmentFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Prophecy\PhpUnit\ProphecyTrait;

final class CreateFromContentTests extends AttachmentFactoryTestCase
{
    use ProphecyTrait;

    public function test_it_creates_an_attachment_from_given_content(): void
    {
        $mimeType = 'application/octet-stream';
        $disposition = $this->getDisposition();
        $factory = new AttachmentFactory(
            $this->getStreamFactory()
        );

        $attachment = $factory->createFromContent("FOO\nBAR\n", $mimeType, $disposition);

        $this->assertInstanceOf(AttachmentInterface::class, $attachment);
        $this->assertSame(
            $mimeType,
            $attachment->getMimeType()
        );
        $this->assertSame(
            $disposition,
            $attachment->getDisposition()
        );
    }
}
