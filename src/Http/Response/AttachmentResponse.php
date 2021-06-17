<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;

final class AttachmentResponse extends Response
{
    /**
     * @param array<string, string> $headers
     */
    public function __construct(AttachmentInterface $attachment, array $headers = [])
    {
        parent::__construct(
            $attachment->getStream(),
            StatusCodeInterface::STATUS_OK,
            $this->prepareHeaders($attachment, $headers)
        );
    }

    /**
     * @param array<string, string> $headers
     *
     * @return array<string, string>
     */
    private function prepareHeaders(AttachmentInterface $attachment, array $headers): array
    {
        $standardHeaders = [
            'Content-Encoding'    => 'none',
            'Content-Type'        => $attachment->getMimeType(),
            'Content-Disposition' => $attachment->getDisposition()
                                         ->toString(),
            'Content-Description' => 'File Transfer',
        ];

        return array_merge($standardHeaders, $headers);
    }
}
