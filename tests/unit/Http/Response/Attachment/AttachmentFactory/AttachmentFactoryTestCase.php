<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Response\Attachment\AttachmentFactory;

use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

abstract class AttachmentFactoryTestCase extends TestCase
{
    /** @noinspection PhpUndefinedMethodInspection */
    protected function getStreamFactory(): StreamFactoryInterface
    {
        /** @var StreamFactoryInterface $streamFactory */
        $streamFactory = $this->prophesize(StreamFactoryInterface::class);

        /** @noinspection PhpStrictTypeCheckingInspection */
        $streamFactory->createStreamFromFile(Argument::cetera())
            ->willReturn(
                $this->getStream()
            );

        /** @noinspection PhpStrictTypeCheckingInspection */
        $streamFactory->createStream(Argument::cetera())
            ->willReturn(
                $this->getStream()
            );

        /** @noinspection PhpStrictTypeCheckingInspection */
        $streamFactory->createStreamFromResource(Argument::cetera())
            ->willReturn(
                $this->getStream()
            );

        return $streamFactory->reveal();
    }

    protected function getStream(): StreamInterface
    {
        $stream = $this->prophesize(StreamInterface::class);

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $stream->reveal();
    }

    protected function getDisposition(): DispositionInterface
    {
        $disposition = $this->prophesize(DispositionInterface::class);

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $disposition->reveal();
    }
}
