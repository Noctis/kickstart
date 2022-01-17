<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Response\Factory\AttachmentResponseFactory;

use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactory;

final class SendContentTests extends AttachmentResponseFactoryTestCase
{
    public function test_it_creates_an_attachment_response(): void
    {
        $factory = new AttachmentResponseFactory(
            $this->getAttachmentFactory()
        );

        $attachmentResponse = $factory->sendContent(
            'foo-content',
            'application/octet-stream',
            $this->getDisposition()
        );

        $this->assertInstanceOf(AttachmentResponse::class, $attachmentResponse);
    }
}
