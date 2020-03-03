<?php declare(strict_types=1);
namespace App\Http\Helper;

use Symfony\Component\HttpFoundation\Request;

trait FlashMessageTrait
{
    /** @var Request */
    protected $request;

    protected function getFlashMessage(string $type = 'flash', bool $persist = false): ?string
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        $flashMessages = $this->request
            ->getSession()
            ->getFlashBag()
            ->get($type);
        $message = !empty($flashMessages)
            ? $flashMessages[0]
            : null;

        if ($persist) {
            $this->setFlashMessage($message, $type);
        }

        return $message;
    }

    protected function setFlashMessage(?string $message, string $type = 'flash'): void
    {
        /** @psalm-suppress UndefinedInterfaceMethod */
        $this->request
            ->getSession()
            ->getFlashBag()
            ->set($type, $message);
    }
}