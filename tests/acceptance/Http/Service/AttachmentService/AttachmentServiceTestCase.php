<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Service\AttachmentService;

use Laminas\Diactoros\StreamFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactory;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactoryInterface;
use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

abstract class AttachmentServiceTestCase extends TestCase
{
    use ProphecyTrait;

    protected function getAttachmentFactory(): AttachmentFactoryInterface
    {
        return new AttachmentFactory(
            new StreamFactory()
        );
    }

    protected function getAttachmentResponseFactory(): AttachmentResponseFactoryInterface
    {
        return new AttachmentResponseFactory();
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
