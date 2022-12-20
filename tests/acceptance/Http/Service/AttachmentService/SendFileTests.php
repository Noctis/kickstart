<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Service\AttachmentService;

use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Service\AttachmentService;

final class SendFileTests extends AttachmentServiceTestCase
{
    private string $tempFilePath;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tempFilePath = tempnam(
            sys_get_temp_dir(),
            'Kickstart'
        );
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        unlink($this->tempFilePath);

        parent::tearDown();
    }

    public function test_it_creates_an_attachment_response(): void
    {
        $service = new AttachmentService(
            $this->getAttachmentFactory(),
            $this->getAttachmentResponseFactory()
        );

        $attachmentResponse = $service->sendFile(
            $this->tempFilePath,
            'application/octet-stream',
            $this->getDisposition()
        );

        $this->assertInstanceOf(AttachmentResponse::class, $attachmentResponse);
    }
}
