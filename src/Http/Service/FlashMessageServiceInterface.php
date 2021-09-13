<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

interface FlashMessageServiceInterface
{
    public function setFlashMessage(string $name, ?string $message): void;

    public function setWarningMessage(string $message): void;

    public function getFlashMessage(string $name, bool $persist = false): ?string;
}
