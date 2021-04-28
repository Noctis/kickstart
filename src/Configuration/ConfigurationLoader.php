<?php

declare(strict_types=1);

namespace Noctis\KickStart\Configuration;

use Dotenv\Dotenv;

final class ConfigurationLoader implements ConfigurationLoaderInterface
{
    /**
     * @inheritDoc
     */
    public function load(string $path, array $requirements = []): void
    {
        $dotenv = Dotenv::createImmutable($path);
        $dotenv->load();

        $requiredOptionsNames = $this->getRequiredOptionsNames($requirements);
        $dotenv->required($requiredOptionsNames)
            ->notEmpty();

        $boolOptionsNames = $this->getBoolOptionsNames($requirements);
        $dotenv->ifPresent($boolOptionsNames)
            ->isBoolean();

        $intOptionsNames = $this->getIntOptionsNames($requirements);
        $dotenv->ifPresent($intOptionsNames)
            ->isInteger();
    }

    /**
     * @param array<string, string> $requirements
     *
     * @return list<string>
     */
    private function getRequiredOptionsNames(array $requirements): array
    {
        return $this->getOptionsWithSpecificRequirement($requirements, 'required');
    }

    /**
     * @param array<string, string> $requirements
     *
     * @return list<string>
     */
    private function getBoolOptionsNames(array $requirements): array
    {
        return $this->getOptionsWithSpecificRequirement($requirements, 'bool');
    }

    /**
     * @param array<string, string> $requirements
     *
     * @return list<string>
     */
    private function getIntOptionsNames(array $requirements): array
    {
        return $this->getOptionsWithSpecificRequirement($requirements, 'int');
    }

    /**
     * @param array<string, string> $requirements
     *
     * @return list<string>
     */
    private function getOptionsWithSpecificRequirement(array $requirements, string $name): array
    {
        return array_keys(
            array_filter(
                array_map(
                    function (string $paramsList): array {
                        return array_map(
                            'trim',
                            explode(',', $paramsList)
                        );
                    },
                    $requirements
                ),
                fn(array $params): bool => in_array($name, $params)
            )
        );
    }
}
