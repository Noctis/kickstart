<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Response\Factory\RedirectResponseFactory;

use Fig\Http\Message\StatusCodeInterface;
use Noctis\KickStart\Http\Response\Factory\RedirectResponseFactory;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class CreateResponseTests extends TestCase
{
    public function test_exception_is_thrown_if_attempting_to_create_a_response_without_setting_uri_first(): void
    {
        $this->expectException(RuntimeException::class);

        $factory = new RedirectResponseFactory();
        $factory->createResponse();
    }

    public function test_a_response_with_status_code_302_is_returned_by_default(): void
    {
        $expectedResult = StatusCodeInterface::STATUS_FOUND;

        $result = (new RedirectResponseFactory())
            ->setUri('https://foo.bar')
            ->createResponse()
            ->getStatusCode();

        $this->assertSame($expectedResult, $result);
    }

    public function test_a_response_with_given_status_code_is_returned(): void
    {
        $expectedResult = StatusCodeInterface::STATUS_MOVED_PERMANENTLY;

        $result = (new RedirectResponseFactory())
            ->setUri('https://foo.bar')
            ->createResponse(StatusCodeInterface::STATUS_MOVED_PERMANENTLY)
            ->getStatusCode();

        $this->assertSame($expectedResult, $result);
    }
}
