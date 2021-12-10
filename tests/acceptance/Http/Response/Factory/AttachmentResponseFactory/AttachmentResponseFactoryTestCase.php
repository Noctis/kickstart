<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Response\Factory\AttachmentResponseFactory;

use Laminas\Diactoros\StreamFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

abstract class AttachmentResponseFactoryTestCase extends TestCase
{
    use ProphecyTrait;

    protected function getAttachmentFactory(): AttachmentFactoryInterface
    {
        return new AttachmentFactory(
            new StreamFactory()
        );
    }

    /** @noinspection PhpUndefinedMethodInspection */
    protected function getDisposition(): DispositionInterface
    {
        /** @var DispositionInterface $disposition */
        $disposition = $this->prophesize(DispositionInterface::class);

        $disposition->toString()
            ->willReturn('foo/bar');

        return $disposition->reveal();
    }
}
