<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Factory;

use Symfony\Component\HttpFoundation\Session\Session;

final class SessionFactory implements SessionFactoryInterface
{
    public function create(): Session
    {
        $session = new Session();
        $session->start();

        return $session;
    }
}
