<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Helper;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment as Twig;

trait RenderTrait
{
    protected Twig $twig;

    protected function render(string $view, array $params = []): Response
    {
        $params['basepath'] = $_ENV['basepath'];

        $html = $this->twig
            ->render($view, $params);

        return new Response($html);
    }
}