<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Laminas\Diactoros\Response;
use Noctis\KickStart\Http\Response\Attachment\AttachmentInterface;

use function Psl\Dict\merge;

final class AttachmentResponse extends Response
{
    /**
     * @inheritDoc
     */
    public function __construct(AttachmentInterface $attachment, int $status = 200, array $headers = [])
    {
        $headers = merge(
            $headers,
            [
                'Content-Encoding'    => 'none',
                'Content-Description' => 'File Transfer',
                'Content-Type'        => $attachment->getMimeType(),
                'Content-Disposition' => $attachment->getDisposition()->toString()
            ]
        );

        parent::__construct(
            $attachment->getStream(),
            $status,
            $headers
        );
    }
}
