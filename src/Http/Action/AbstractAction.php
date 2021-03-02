<?php declare(strict_types=1);
namespace Noctis\KickStart\Http\Action;

use Noctis\KickStart\Configuration\ConfigurationInterface;
use Noctis\KickStart\Http\Helper\FlashMessageTrait;
use Noctis\KickStart\Http\Helper\HttpRedirectionTrait;
use Noctis\KickStart\Service\TemplateRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAction
{
    use FlashMessageTrait, HttpRedirectionTrait;

    protected ConfigurationInterface $configuration;
    protected TemplateRendererInterface $templateRenderer;
    protected Request $request;

    public function __construct(
        ConfigurationInterface $configuration,
        TemplateRendererInterface $templateRenderer,
        Request $request
    ) {
        $this->configuration = $configuration;
        $this->templateRenderer = $templateRenderer;
        $this->setRequest($request);
    }

    protected function render(string $view, array $params = []): Response
    {
        $params['basehref'] = $this->configuration
            ->get('basehref');

        $html = $this->templateRenderer
            ->render($view, $params);

        return new Response($html);
    }

    protected function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    protected function notFound(): Response
    {
        return new Response(
            '404, bro!',
            Response::HTTP_NOT_FOUND
        );
    }
}