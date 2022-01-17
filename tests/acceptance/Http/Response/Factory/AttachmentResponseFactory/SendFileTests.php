<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Response\Factory\AttachmentResponseFactory;

use Noctis\KickStart\Http\Response\AttachmentResponse;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactory;

final class SendFileTests extends AttachmentResponseFactoryTestCase
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
        $factory = new AttachmentResponseFactory(
            $this->getAttachmentFactory()
        );

        $attachmentResponse = $factory->sendFile(
            $this->tempFilePath,
            'application/octet-stream',
            $this->getDisposition()
        );

        $this->assertInstanceOf(AttachmentResponse::class, $attachmentResponse);
    }
}
