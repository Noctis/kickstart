<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Configuration\Configuration;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Noctis\KickStart\Service\Twig\Extension\UrlExtension;
use Noctis\KickStart\Service\TwigRenderer;
use Psr\Container\ContainerInterface;
use Twig\Environment as Twig;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

final class TwigServiceProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            TemplateRendererInterface::class  => TwigRenderer::class,
            Twig::class => function (ContainerInterface $container): Twig {
                /** @var string $basePath */
                $basePath = Configuration::get('basepath');
                $debugMode = !Configuration::isProduction();

                $loader = new FilesystemLoader($basePath . '/templates');
                $twig = new Twig($loader, [
                    'cache'            => $debugMode === true
                                            ? false
                                            : $basePath . '/var/cache/templates',
                    'debug'            => $debugMode,
                    'strict_variables' => $debugMode,
                ]);
                $twig->addExtension(
                    new DebugExtension()
                );
                $twig->addExtension(
                    $container->get(UrlExtension::class)
                );

                return $twig;
            },
        ];
    }
}
