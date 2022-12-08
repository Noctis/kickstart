<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Service;

use Laminas\Session\Container;

final class FlashMessageService implements FlashMessageServiceInterface
{
    public function __construct(private Container $flashContainer)
    {
    }

    public function setFlashMessage(string $name, ?string $message): void
    {
        $this->flashContainer[$name] = $message;
    }

    public function setWarningMessage(string $message): void
    {
        $this->setFlashMessage('warning', $message);
    }

    public function getFlashMessage(string $name, bool $persist = false): ?string
    {
        /** @var string|null $message */
        $message = $this->flashContainer[$name] ?? null;
        unset($this->flashContainer[$name]);

        if ($message !== null && $persist) {
            $this->setFlashMessage($name, $message);
        }

        return $message;
    }
}
