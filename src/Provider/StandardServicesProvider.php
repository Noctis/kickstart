<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Laminas\Diactoros\StreamFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface;
use Noctis\KickStart\Service\Container\PhpDiContainer;
use Noctis\KickStart\Service\Container\SettableContainerInterface;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Noctis\KickStart\Service\TwigRenderer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class StandardServicesProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            AttachmentFactoryInterface::class => AttachmentFactory::class,
            ContainerInterface::class => PhpDiContainer::class,
            SettableContainerInterface::class => PhpDiContainer::class,
            StreamFactoryInterface::class => StreamFactory::class,
            TemplateRendererInterface::class  => TwigRenderer::class,
        ];
    }
}
