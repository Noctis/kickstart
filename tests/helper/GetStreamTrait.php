<?php

declare(strict_types=1);

namespace Tests\Helper;

use Psr\Http\Message\StreamInterface;

trait GetStreamTrait
{
    function getStream(): StreamInterface
    {
        return $this->prophesize(StreamInterface::class)
            ->reveal();
    }
}
