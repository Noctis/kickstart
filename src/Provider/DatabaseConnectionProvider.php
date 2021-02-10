<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

use ParagonIE\EasyDB\EasyDB;
use ParagonIE\EasyDB\Exception\ConstructorFailed;
use ParagonIE\EasyDB\Factory;

final class DatabaseConnectionProvider implements ServicesProviderInterface
{
    /**
     * @return callable[]
     */
    public function getServicesDefinitions(): array
    {
        return [
            EasyDB::class => function (): EasyDB {
                try {
                    return Factory::fromArray([
                        sprintf(
                            'mysql:dbname=%s;host=%s;port=%s',
                            $_ENV['db_name'],
                            $_ENV['db_host'],
                            $_ENV['db_port']
                        ),
                        $_ENV['db_user'],
                        $_ENV['db_pass']
                    ]);
                } catch (ConstructorFailed $ex) {
                    die('Could not connect to DB: '. $ex->getMessage());
                }
            },
        ];
    }
}