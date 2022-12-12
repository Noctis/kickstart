<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Laminas\Diactoros\StreamFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface;
use Noctis\KickStart\Service\PathGenerator;
use Noctis\KickStart\Service\PathGeneratorInterface;
use Noctis\KickStart\Service\UrlGenerator;
use Noctis\KickStart\Service\UrlGeneratorInterface;
use Noctis\KickStart\Service\Container\DefinitionNormalizerInterface;
use Noctis\KickStart\Service\Container\PhpDi\Container;
use Noctis\KickStart\Service\Container\PhpDi\DefinitionNormalizer;
use Noctis\KickStart\Service\Container\SettableContainerInterface;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Noctis\KickStart\Service\TwigRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamFactoryInterface;

use function Noctis\KickStart\Service\Container\autowire;
use function Noctis\KickStart\Service\Container\reference;

final class StandardServicesProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            AttachmentFactoryInterface::class => AttachmentFactory::class,
            ContainerInterface::class => Container::class,
            DefinitionNormalizerInterface::class => DefinitionNormalizer::class,
            PathGeneratorInterface::class => autowire(PathGenerator::class)
                ->method(
                    'setRoutes',
                    reference('__routes')
                ),
            SettableContainerInterface::class => Container::class,
            StreamFactoryInterface::class => StreamFactory::class,
            TemplateRendererInterface::class  => TwigRenderer::class,
            UrlGeneratorInterface::class => UrlGenerator::class,
        ];
    }
}
