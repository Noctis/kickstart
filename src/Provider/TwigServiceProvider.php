<?php declare(strict_types=1);
namespace App\Provider;

use Psr\Container\ContainerInterface;
use Twig\Environment as Twig;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

final class TwigServiceProvider implements ServicesProviderInterface
{
    private string $path;
    private string $env;

    public function __construct(string $path, string $env)
    {
        $this->path = $path;
        $this->env = $env;
    }

    /**
     * @return callable[]
     */
    public function getServicesDefinitions(): array
    {
        return [
            Twig::class => $this->twigFactory(),
        ];
    }

    private function twigFactory(): callable
    {
        return function (ContainerInterface $container): Twig {
            $loader = new FilesystemLoader($this->path .'/templates');
            $twig = new Twig($loader, [
                'cache' => false,
                'debug' => $this->env === 'dev',
            ]);

            $twig->addExtension(
                new DebugExtension()
            );

            return $twig;
        };
    }
}