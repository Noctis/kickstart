<?php

declare(strict_types=1);

namespace Tests\Acceptance\Http\Service\RedirectService;

final class RedirectToPathTests extends AbstractRedirectServiceTestCase
{
    public function test_it_builds_proper_redirection_destination_with_query_params_provided(): void
    {
        $service = $this->getRedirectService();
        $expectedResult = 'https://localhost/product/15/show?highlight=foo&mobile=true';

        $result = $service->redirectToPath('/product/15/show', ['highlight' => 'foo', 'mobile' => 'true'])
            ->getHeader('location')[0];

        $this->assertSame($expectedResult, $result);
    }

    public function test_it_builds_proper_redirection_destination_with_no_query_params(): void
    {
        $service = $this->getRedirectService();
        $expectedResult = 'https://localhost/product/15/show';

        $result = $service->redirectToPath('/product/15/show')
            ->getHeader('location')[0];

        $this->assertSame($expectedResult, $result);
    }

    public function test_it_builds_proper_redirection_when_given_path_is_an_absolute_url(): void
    {
        $service = $this->getRedirectService();
        $expectedResult = 'https://example.com/about-as';

        $result = $service->redirectToPath('https://example.com/about-as')
            ->getHeader('location')[0];

        $this->assertSame($expectedResult, $result);
    }
}
