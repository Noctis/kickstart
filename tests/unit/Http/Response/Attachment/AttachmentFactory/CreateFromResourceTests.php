<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Response\Attachment\AttachmentFactory;

use Noctis\KickStart\Http\Response\Attachment\AttachmentFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * @covers AttachmentFactory::createFromResource()
 */
final class CreateFromResourceTests extends AttachmentFactoryTestCase
{
    use ProphecyTrait;

    /** @var resource */
    private $resource;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->resource = fopen('php://temp', 'r');
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        fclose($this->resource);

        parent::tearDown();
    }

    public function test_it_creates_an_attachment_from_given_resource(): void
    {
        $mimeType = 'application/octet-stream';
        $disposition = $this->getDisposition();
        $factory = new AttachmentFactory(
            $this->getStreamFactory()
        );

        $attachment = $factory->createFromResource($this->resource, $mimeType, $disposition);

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
