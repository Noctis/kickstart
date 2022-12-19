<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response;

use Laminas\Diactoros\Response;

use function Psl\Dict\merge;

final class AttachmentResponse extends Response
{
    /**
     * @inheritDoc
     */
    public function __construct($body = 'php://memory', int $status = 200, array $headers = [])
    {
        $headers = merge(
            $headers,
            [
                'Content-Encoding'    => 'none',
                'Content-Description' => 'File Transfer',
            ]
        );

        parent::__construct($body, $status, $headers);
    }
}
