<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Laminas\Diactoros\StreamFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactory;
use Noctis\KickStart\Http\Response\Attachment\AttachmentFactoryInterface;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Noctis\KickStart\Service\TwigRenderer;
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
            StreamFactoryInterface::class => StreamFactory::class,
            TemplateRendererInterface::class  => TwigRenderer::class,
        ];
    }
}
