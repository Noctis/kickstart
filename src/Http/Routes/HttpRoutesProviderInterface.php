<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Routes;

interface HttpRoutesProviderInterface
{
    public function get(): callable;
}