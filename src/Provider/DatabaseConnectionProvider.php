<?php declare(strict_types=1);
namespace App\Provider;

use App\Service\DatabaseConnection\DatabaseConnectionInterface;
use App\Service\DatabaseConnection\EasyDB as EasyDBDatabaseConnection;
use PDOException;

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
                    return new EasyDBDatabaseConnection(
                        sprintf(
                            'mysql:dbname=%s;host=%s',
                            getenv('db_name'),
                            getenv('db_host')
                        ),
                        getenv('db_user'),
                        getenv('db_pass')
                    );
                } catch (PDOException $ex) {
                    die('Could not connect to DB: '. $ex->getMessage());
                }
            },
        ];
    }
}