<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helpers;

use Laminas\Session\Container;

interface FlashMessageHelperInterface
{
    public function setFlashMessage(string $message): void;

    public function getFlashMessage(bool $persist = false): ?string;
}
