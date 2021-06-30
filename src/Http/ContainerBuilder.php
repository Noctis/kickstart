<?php

declare(strict_types=1);

namespace Noctis\KickStart\Http;

use Noctis\KickStart\AbstractContainerBuilder;
use Noctis\KickStart\ContainerBuilderInterface;
use Noctis\KickStart\Provider\ConfigurationProvider;
use Noctis\KickStart\Provider\HttpServicesProvider;
use Noctis\KickStart\Provider\StandardServicesProvider;
use Noctis\KickStart\Provider\TwigServiceProvider;

final class ContainerBuilder extends AbstractContainerBuilder implements ContainerBuilderInterface
{
    public function __construct()
    {
        parent::__construct();

        $this
            ->registerServicesProvider(new ConfigurationProvider())
            ->registerServicesProvider(new HttpServicesProvider())
            ->registerServicesProvider(new TwigServiceProvider(
                (string)$_ENV['basepath']
            ))
            ->registerServicesProvider(new StandardServicesProvider())
        ;
    }
}
