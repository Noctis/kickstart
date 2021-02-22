<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

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
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            Twig::class => function (): Twig {
                $loader = new FilesystemLoader($this->path .'/templates');
                $twig = new Twig($loader, [
                    'cache' => false,
                    'debug' => $this->env === 'dev',
                ]);

                $twig->addExtension(
                    new DebugExtension()
                );

                return $twig;
            }
        ];
    }
}