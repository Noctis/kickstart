<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface RouterInterface
{
    /**
     * @return array
     */
    public function getDispatchInfo(ServerRequestInterface $request): array;
}
