<?php

declare(strict_types=1);

namespace Noctis\KickStart;

use Noctis\KickStart\Provider\ServicesProviderInterface;
use Psr\Container\ContainerInterface;

interface ContainerBuilderInterface
{
    public function registerServicesProvider(ServicesProviderInterface $servicesProvider): self;

    public function build(): ContainerInterface;
}
