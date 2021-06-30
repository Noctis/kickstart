<?php

declare(strict_types=1);

namespace Noctis\KickStart;

abstract class AbstractApplication
{
    abstract public function run(): void;
}
