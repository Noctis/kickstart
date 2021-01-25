<?php declare(strict_types=1);
namespace Noctis\KickStart\Http;

use DI\Container as DiContainer;
use FastRoute\Dispatcher;
use Noctis\KickStart\Http\Middleware\RequestHandlerStack;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Router
{
    private Dispatcher $dispatcher;
    private DiContainer $container;

    public function __construct(Dispatcher $dispatcher, DiContainer $container)
    {
        $this->dispatcher = $dispatcher;
        $this->container = $container;
    }

    public function route(): void
    {
        $routeInfo = $this->determineRouteInfo();

        switch ($routeInfo[0]) {
            // ... 200 Found
            case Dispatcher::FOUND:
                $response = $this->found($routeInfo);
                break;

            // ... 404 Not Found
            case Dispatcher::NOT_FOUND:
                $response = $this->notFound();
                break;

            // ... 405 Method Not Allowed
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response = $this->methodNotAllowed($routeInfo);
                break;

            default:
                throw new RuntimeException();
        }

        $response->send();
    }

    private function determineRouteInfo(): array
    {
        // Fetch method and URI from somewhere
        $httpMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD', FILTER_SANITIZE_STRING);
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        return $this->dispatcher
            ->dispatch($httpMethod, $uri);
    }

    private function found(array $routeInfo): Response
    {
        $handler = $routeInfo[1];
        $this->container
            ->set('request.vars', $routeInfo[2]);

        if (count($handler) === 2) {
            [$actionClassName, $guardsNames] = $handler;
        } else {
            [$actionClassName] = $handler;
        }

        $stack = new RequestHandlerStack($this->container, $actionClassName, $guardsNames ?? []);

        return $stack->handle(
            $this->container
                ->get(Request::class)
        );
    }

    private function notFound(): Response
    {
        return new Response(
            '404, bro!',
            Response::HTTP_NOT_FOUND
        );
    }

    private function methodNotAllowed(array $routeInfo): Response
    {
        $allowedMethods = $routeInfo[1];

        return new Response(
            sprintf(
                'Allowed methods: %s.',
                implode(', ', $allowedMethods)
            ),
            Response::HTTP_METHOD_NOT_ALLOWED
        );
    }
}