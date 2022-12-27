<?php

declare(strict_types=1);

namespace Noctis\KickStart\Console\Service;

use Noctis\KickStart\Console\Command\AbstractCommand;

interface CommandNameExtractorInterface
{
    /**
     * @param class-string<AbstractCommand> $commandClassName
     */
    public function extract(string $commandClassName): string;
}
