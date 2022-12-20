<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Response\Factory\AttachmentResponseFactory;

use Fig\Http\Message\StatusCodeInterface;
use Noctis\KickStart\Http\Response\Factory\AttachmentResponseFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use RuntimeException;
use Tests\Helper\GetAttachmentTrait;
use Tests\Helper\GetDispositionTrait;
use Tests\Helper\GetStreamTrait;

final class CreateResponseTests extends TestCase
{
    use GetAttachmentTrait;
    use GetDispositionTrait;
    use GetStreamTrait;
    use ProphecyTrait;

    public function test_exception_is_thrown_if_attempting_to_create_a_response_without_setting_attachment_first(): void
    {
        $this->expectException(RuntimeException::class);

        $factory = new AttachmentResponseFactory();
        $factory->createResponse();
    }

    public function test_it_creates_an_response_with_given_status_code(): void
    {
        $statusCode = StatusCodeInterface::STATUS_NOT_FOUND;
        $expectedResult = $statusCode;

        $result = (new AttachmentResponseFactory())
            ->setAttachment(
                $this->getAttachment('foo/bar', 'attachment')
            )
            ->createResponse($statusCode)
            ->getStatusCode();

        $this->assertSame($expectedResult, $result);
    }
}
