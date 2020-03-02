<?php declare(strict_types=1);
namespace App\Provider;

use ParagonIE\EasyDB\EasyDB;
use ParagonIE\EasyDB\Factory as EasyDBFactory;

final class EasyDBServiceProvider implements ServicesProviderInterface
{
    /**
     * @return callable[]
     */
    public function getServicesDefinitions(): array
    {
        return [
            EasyDB::class => $this->easyDbFactory(),
        ];
    }

    private function easyDbFactory(): callable
    {
        return function (): EasyDB {
            /** @psalm-suppress PossiblyFalseArgument */
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s',
                getenv('db_host'),
                getenv('db_name')
            );

            return EasyDBFactory::fromArray([
                $dsn,
                getenv('db_user'),
                getenv('db_pass')
            ]);
        };
    }
}