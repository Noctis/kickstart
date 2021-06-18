<?php

declare(strict_types=1);

namespace Noctis\KickStart\Service;

interface TemplateRendererInterface
{
    public function render(string $template, array $params = []): string;

    public function registerFunction(string $name, callable $function): void;
}
