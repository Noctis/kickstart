<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Helper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

trait FlashMessageTrait
{
    protected Request $request;

    protected function getFlashMessage(string $type = 'flash', bool $persist = false): ?string
    {
        /** @var Session $session */
        $session = $this->request
            ->getSession();

        /** @var list<string> $flashMessages */
        $flashMessages = $session->getFlashBag()
            ->get($type);

        $message = $flashMessages[0] ?? null;

        if ($persist && $message !== null) {
            $this->setFlashMessage($message, $type);
        }

        return $message;
    }

    protected function setFlashMessage(string $message, string $type = 'flash'): void
    {
        /** @var Session $session */
        $session = $this->request
            ->getSession();

        $session->getFlashBag()
            ->set($type, $message);
    }
}
