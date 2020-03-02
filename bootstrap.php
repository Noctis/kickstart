<?php declare(strict_types=1);

use App\Configuration;
use App\ContainerBuilder;
use Psr\Container\ContainerInterface;

require_once __DIR__ .'/vendor/autoload.php';

$env = 'dev';
if ($env === 'dev') {
    ini_set('display_errors', 'On');
    error_reporting(E_ALL ^ E_NOTICE);
}
putenv('BASEDIR='. __DIR__);

(new Configuration())
    ->load(__DIR__);

function get_container(string $env): ContainerInterface
{
    return (new ContainerBuilder())
        ->build(__DIR__, $env);
}