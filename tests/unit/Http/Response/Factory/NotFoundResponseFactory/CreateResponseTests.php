<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Response\Factory\NotFoundResponseFactory;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\TextResponse;
use Noctis\KickStart\Http\Response\Factory\NotFoundResponseFactory;
use PHPUnit\Framework\TestCase;

final class CreateResponseTests extends TestCase
{
    public function test_an_instance_of_empty_response_is_returned_by_default(): void
    {
        $result = (new NotFoundResponseFactory())
            ->createResponse();

        $this->assertInstanceOf(EmptyResponse::class, $result);
    }

    public function test_an_instance_of_text_response_is_returned_if_reason_is_given(): void
    {
        $result = (new NotFoundResponseFactory())
            ->createResponse(reasonPhrase: 'lolnope!');

        $this->assertInstanceOf(TextResponse::class, $result);
        $this->assertSame(
            'lolnope!',
            (string)$result->getBody()
        );
    }

    public function test_a_response_with_status_code_404_is_returned_by_default(): void
    {
        $expectedResult = StatusCodeInterface::STATUS_NOT_FOUND;

        $result = (new NotFoundResponseFactory())
            ->createResponse()
            ->getStatusCode();

        $this->assertSame($expectedResult, $result);
    }

    public function test_given_status_code_is_ignored(): void
    {
        $expectedResult = StatusCodeInterface::STATUS_NOT_FOUND;

        $result = (new NotFoundResponseFactory())
            ->createResponse(StatusCodeInterface::STATUS_MOVED_PERMANENTLY)
            ->getStatusCode();

        $this->assertSame($expectedResult, $result);
    }
}
