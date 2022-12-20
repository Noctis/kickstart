<?php

declare(strict_types=1);

namespace Tests\Helper;

use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;
use Prophecy\Prophecy\ObjectProphecy;

trait GetAttachmentTrait
{
    protected function getAttachment(string $mimeType, string $disposition): AttachmentInterface
    {
        /** @var AttachmentInterface $attachment */
        $attachment = $this->prophesize(AttachmentInterface::class);

        $attachment->getStream()
            ->willReturn(
                $this->getStream()
            );
        $attachment->getMimeType()
            ->willReturn($mimeType);

        $attachment->getDisposition()
            ->willReturn(
                $this->getDisposition($disposition)
            );

        /** @var ObjectProphecy $attachment */
        return $attachment->reveal();
    }
}
