<?php

declare(strict_types=1);

namespace Noctis\KickStart\Console;

use Noctis\KickStart\AbstractContainerBuilder;
use Noctis\KickStart\ContainerBuilderInterface;
use Noctis\KickStart\Provider\ConfigurationProvider;

final class ContainerBuilder extends AbstractContainerBuilder implements ContainerBuilderInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->registerServicesProvider(new ConfigurationProvider());
    }
}
