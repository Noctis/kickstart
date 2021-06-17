<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Response\Headers;

interface HeaderInterface
{
    public function toString(): string;
}
