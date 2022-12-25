<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Response\Attachment\AttachmentResponse;

use Fig\Http\Message\StatusCodeInterface;
use Noctis\KickStart\Http\Response\AttachmentResponse;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\Helper\GetAttachmentTrait;
use Tests\Helper\GetDispositionTrait;
use Tests\Helper\GetStreamTrait;

final class AttachmentResponseTests extends TestCase
{
    use GetAttachmentTrait;
    use GetDispositionTrait;
    use GetStreamTrait;
    use ProphecyTrait;

    public function test_it_sets_its_headers_based_on_given_attachment(): void
    {
        $attachment = $this->getAttachment('foo/bar', 'attachment');
        $expectedResult = [
            'Content-Encoding'    => ['none'],
            'Content-Description' => ['File Transfer'],
            'Content-Type'        => ['foo/bar'],
            'Content-Disposition' => ['attachment'],
        ];

        $result = (new AttachmentResponse($attachment))
            ->getHeaders();

        $this->assertEquals($expectedResult, $result);
    }

    public function test_standard_headers_overwrite_given_headers_if_there_is_a_conflict(): void
    {
        // `Content-Type: text/plain` should be overridden by attachment's `Content-Type: foo/bar`
        // `X-Foo: bar` should stay as-is
        $headers = [
            'Content-Type' => 'text/plain',
            'X-Foo'        => 'bar',
        ];
        $attachment = $this->getAttachment('foo/bar', 'attachment');
        $expectedResult = [
            'Content-Encoding'    => ['none'],
            'Content-Description' => ['File Transfer'],
            'Content-Type'        => ['foo/bar'],
            'Content-Disposition' => ['attachment'],
            'X-Foo'               => ['bar'],
        ];

        $result = (new AttachmentResponse($attachment, headers: $headers))
            ->getHeaders();

        $this->assertEquals($expectedResult, $result);
    }

    public function test_200_ok_status_code_is_used_by_default_in_returned_response(): void
    {
        $attachment = $this->getAttachment('foo/bar', 'attachment');
        $expectedResult = StatusCodeInterface::STATUS_OK;

        $result = (new AttachmentResponse($attachment))
            ->getStatusCode();

        $this->assertSame($expectedResult, $result);
    }

    public function test_given_status_code_is_used_in_returned_response(): void
    {
        $attachment = $this->getAttachment('foo/bar', 'attachment');
        $expectedResult = StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE;

        $result = (new AttachmentResponse($attachment, StatusCodeInterface::STATUS_SERVICE_UNAVAILABLE))
            ->getStatusCode();

        $this->assertSame($expectedResult, $result);
    }
}
