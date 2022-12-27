<?php

declare(strict_types=1);

namespace Noctis\KickStart\Provider;

use Noctis\KickStart\Console\Service\CommandNameExtractor;
use Noctis\KickStart\Console\Service\CommandNameExtractorInterface;

final class ConsoleServicesProvider implements ServicesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function getServicesDefinitions(): array
    {
        return [
            CommandNameExtractorInterface::class => CommandNameExtractor::class,
        ];
    }
}
