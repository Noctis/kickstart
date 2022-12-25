<?php

declare(strict_types=1);

namespace Tests\Helper;

use Noctis\KickStart\Http\Response\Headers\DispositionInterface;
use Prophecy\Prophecy\ObjectProphecy;

trait GetDispositionTrait
{
    protected function getDisposition(string $value): DispositionInterface
    {
        /** @var DispositionInterface $disposition */
        $disposition = $this->prophesize(DispositionInterface::class);

        $disposition->toString()
            ->willReturn($value);

        /** @var ObjectProphecy $disposition */
        return $disposition->reveal();
    }
}
