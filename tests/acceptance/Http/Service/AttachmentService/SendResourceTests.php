<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Service\AttachmentService;

use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Service\AttachmentService;

final class SendResourceTests extends AttachmentServiceTestCase
{
    public function test_it_creates_an_attachment_response(): void
    {
        $service = new AttachmentService(
            $this->getAttachmentFactory(),
            $this->getAttachmentResponseFactory()
        );

        $attachmentResponse = $service->sendResource(
            tmpfile(),
            'application/octet-stream',
            $this->getDisposition()
        );

        $this->assertInstanceOf(AttachmentResponse::class, $attachmentResponse);
    }


}
