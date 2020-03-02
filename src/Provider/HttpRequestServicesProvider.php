<?php declare(strict_types=1);
namespace App\Provider;

use DI\Factory\RequestedEntry;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

final class HttpRequestServicesProvider implements ServicesProviderInterface
{
    /**
     * @return callable[]
     */
    public function getServicesDefinitions(): array
    {
        return [
            Session::class => $this->sessionFactory(),
            Request::class => $this->requestFactory(),
            'App\Http\Request\*' => $this->requestFactory(),
        ];
    }

    private function sessionFactory(): callable
    {
        return function (): Session {
            $session = new Session();
            $session->start();

            return $session;
        };
    }

    private function requestFactory(): callable
    {
        return function (RequestedEntry $entry, ContainerInterface $c): Request {
            $requestClassName = $entry->getName();
            /** @psalm-suppress InvalidStringClass */
            $request = $requestClassName::createFromGlobals();
            /** @var Request $request */
            $request->setSession(
                $c->get(Session::class)
            );

            return $request;
        };
    }
}