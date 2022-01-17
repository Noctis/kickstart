<?php

declare(strict_types=1);

namespace Tests\Unit\Service\TwigRenderer;

use Noctis\KickStart\Service\TwigRenderer;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Twig\Environment as Twig;
use Twig\Extension\ExtensionInterface;

final class TwigRendererTests extends TestCase
{
    use ProphecyTrait;

    public function test_twigs_render_method_is_called(): void
    {
        $twig = $this->getTwigMock();
        $twigRenderer = new TwigRenderer(
            $twig->reveal()
        );

        $twigRenderer->render('foo.html.twig', ['foo' => 'bar']);

        $twig->render(Argument::cetera())
            ->shouldBeCalledTimes(1);
    }

    public function test_twigs_add_function_is_called(): void
    {
        $twig = $this->getTwigMock();
        $twigRenderer = new TwigRenderer(
            $twig->reveal()
        );

        $twigRenderer->registerFunction(
            'foo',
            fn (): string => 'foo'
        );

        $twig->addFunction(Argument::cetera())
            ->shouldBeCalledTimes(1);
    }

    public function test_twigs_add_extensions_is_called(): void
    {
        $twig = $this->getTwigMock();
        $twigRenderer = new TwigRenderer(
            $twig->reveal()
        );

        $twigRenderer->registerExtension(
            $this->getTwigExtension()
        );

        $twig->addExtension(Argument::cetera())
            ->shouldBeCalledTimes(1);
    }

    private function getTwigMock(): ObjectProphecy
    {
        $twig = $this->prophesize(Twig::class);

        $twig->render(Argument::cetera())
            ->willReturn('');

        return $twig;
    }

    private function getTwigExtension(): ExtensionInterface
    {
        $extension = $this->prophesize(ExtensionInterface::class);

        return $extension->reveal();
    }
}
