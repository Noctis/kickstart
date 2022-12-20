<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Response\Factory\HtmlResponseFactory;

use Fig\Http\Message\StatusCodeInterface;
use Noctis\KickStart\Http\Response\Factory\HtmlResponseFactory;
use PHPUnit\Framework\TestCase;

final class CreateResponseTests extends TestCase
{
    public function test_a_response_it_creates_has_an_empty_body(): void
    {
        $result = (string)(new HtmlResponseFactory())
            ->createResponse()
            ->getBody();

        $this->assertSame('', $result);
    }

    public function test_a_response_with_status_code_200_is_returned_by_default(): void
    {
        $expectedResult = StatusCodeInterface::STATUS_OK;

        $result = (new HtmlResponseFactory())
            ->createResponse()
            ->getStatusCode();

        $this->assertSame($expectedResult, $result);
    }

    public function test_it_creates_an_response_with_given_status_code(): void
    {
        $expectedResult = StatusCodeInterface::STATUS_NOT_FOUND;

        $result = (new HtmlResponseFactory())
            ->createResponse(StatusCodeInterface::STATUS_NOT_FOUND)
            ->getStatusCode();

        $this->assertSame($expectedResult, $result);
    }
}
