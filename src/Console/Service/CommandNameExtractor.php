<?php

declare(strict_types=1);

namespace Noctis\KickStart\Console\Service;

use ReflectionClass;
use ReflectionException;
use RuntimeException;
use Symfony\Component\Console\Attribute\AsCommand;

final class CommandNameExtractor implements CommandNameExtractorInterface
{
    /**
     * @inheritDoc
     */
    public function extract(string $commandClassName): string
    {
        $reflection = new ReflectionClass($commandClassName);
        $name = $this->getNameFromAttributes($reflection);
        if ($name !== null) {
            return $name;
        }

        $name = $this->getNameFromProperty($reflection);
        if ($name !== null) {
            return $name;
        }

        throw new RuntimeException(
            sprintf(
                'Could not determine command name from class "%s".',
                $commandClassName
            )
        );
    }

    private function getNameFromAttributes(ReflectionClass $reflection): ?string
    {
        $attributes = $reflection->getAttributes(AsCommand::class);
        if ($attributes === []) {
            return null;
        }

        /** @var string|null */
        return $attributes[0]->getArguments()['name'] ?? null;
    }

    private function getNameFromProperty(ReflectionClass $reflection): ?string
    {
        try {
            /** @var string|null */
            return $reflection->getProperty('defaultName')
                ->getValue();
        } catch (ReflectionException $ex) {
            return null;
        }
    }
}
