<?php declare(strict_types=1);
namespace Noctis\KickStart\Provider;

use Noctis\Database\Connection\DatabaseConnectionInterface;
use Noctis\Database\Connection\EasyDB as EasyDBDatabaseConnection;
use ParagonIE\EasyDB\Exception\ConstructorFailed;

final class DatabaseConnectionProvider implements ServicesProviderInterface
{
    /**
     * @return callable[]
     */
    public function getServicesDefinitions(): array
    {
        return [
            DatabaseConnectionInterface::class => function (): EasyDBDatabaseConnection {
                try {
                    /** @psalm-suppress PossiblyFalseArgument */
                    return EasyDBDatabaseConnection::create(
                        sprintf(
                            'mysql:dbname=%s;host=%s;port=%s',
                            $_ENV['db_name'],
                            $_ENV['db_host'],
                            $_ENV['db_port']
                        ),
                        $_ENV['db_user'],
                        $_ENV['db_pass']
                    );
                } catch (ConstructorFailed $ex) {
                    die('Could not connect to DB: '. $ex->getMessage());
                }
            },
        ];
    }
}