<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Service\RedirectService;

use Noctis\KickStart\Http\Routing\Route;

final class RedirectToRouteTests extends AbstractRedirectServiceTestCase
{
    public function test_it_builds_proper_redirection_destination_when_given_route_exists(): void
    {
        $service = $this->getRedirectService();
        $expectedResult = 'https://localhost/manual/15/download?format=pdf';

        $result = $service->redirectToRoute('download-manual', ['id' => '15', 'format' => 'pdf'])
            ->getHeader('location')[0];

        $this->assertSame($expectedResult, $result);
    }

    public function test_it_builds_proper_redirection_destination_for_route_with_params(): void
    {
        $service = $this->getRedirectService();
        $expectedResult = 'https://localhost/sign-in/form';

        $result = $service->redirectToRoute('sign-in')
            ->getHeader('location')[0];

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @inheritDoc
     */
    protected function getRoutes(): array
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        return [
            'show-product'    => Route::get('/product/{id:/d}/show', '\App\Http\Action\ShowProductAction'),
            'download-manual' => Route::get('/manual/{id}/download', '\App\Http\Action\DownloadManualAction'),
            'sign-in'         => Route::get('/sign-in/form', '\App\Http\Action\SignInFormAction'),
        ];
    }
}
