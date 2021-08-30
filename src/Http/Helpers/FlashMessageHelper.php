<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helpers;

use Laminas\Session\Container as SessionContainer;

final class FlashMessageHelper implements FlashMessageHelperInterface
{
    private SessionContainer $flashContainer;

    public function __construct()
    {
        $this->flashContainer = new SessionContainer('flash');
    }

    public function setFlashMessage(string $message): void
    {
        $this->flashContainer['message'] = $message;
    }

    public function getFlashMessage(bool $persist = false): ?string
    {
        /** @var string|null $message */
        $message = $this->flashContainer['message'] ?? null;
        unset($this->flashContainer['message']);

        if ($message !== null && $persist) {
            $this->setFlashMessage($message);
        }

        return $message;
    }
}
