<?php declare(strict_types=1);
namespace App\Http\Factory;

use DI\Factory\RequestedEntry;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

interface RequestFactoryInterface
{
    public function createFromGlobals(RequestedEntry $entry, ContainerInterface $c, array $vars = []): Request;
}