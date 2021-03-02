<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Configuration\ConfigurationInterface;
use Psr\Container\ContainerInterface;
use Twig\Environment as Twig;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

final class TwigServiceProvider implements ServicesProviderInterface
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            Twig::class => function (ContainerInterface $container): Twig {
                $configuration = $container->get(ConfigurationInterface::class);
                $debugMode = $configuration->get('debug') === true;

                $loader = new FilesystemLoader($this->basePath .'/templates');
                $twig = new Twig($loader, [
                    'cache'            => $debugMode === true
                                            ? false
                                            : $this->basePath .'/var/cache/templates',
                    'debug'            => $debugMode,
                    'strict_variables' => $debugMode,
                ]);

                $twig->addExtension(
                    new DebugExtension()
                );

                return $twig;
            }
        ];
    }
}