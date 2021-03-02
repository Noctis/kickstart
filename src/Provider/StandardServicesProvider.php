<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Service\TemplateRendererInterface;
use Noctis\KickStart\Service\TwigRenderer;
use function DI\autowire;

final class StandardServicesProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            TemplateRendererInterface::class => autowire(TwigRenderer::class),
        ];
    }
}