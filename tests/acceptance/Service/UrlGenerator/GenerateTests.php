<?php

declare(strict_types=1);

namespace Tests\Acceptance\Service\UrlGenerator;

use Noctis\KickStart\Service\UrlGenerator;
use PHPUnit\Framework\TestCase;

final class GenerateTests extends TestCase
{
    public function test_it_correctly_handles_a_route_with_no_placeholders_when_no_params_were_given(): void
    {
        $route = '/foo/bar';
        $params = [];
        $expectedResult = '/foo/bar';

        $result = (new UrlGenerator())->generate($route, $params);

        $this->assertSame(
            $expectedResult,
            $result->toString()
        );
    }

    public function test_it_builds_query_string_from_given_params_when_given_route_has_no_placeholders(): void
    {
        $route = '/foo/bar';
        $params = ['query' => 'baz', 'page' => 2];
        $expectedResult = '/foo/bar?query=baz&page=2';

        $result = (new UrlGenerator())->generate($route, $params);

        $this->assertSame(
            $expectedResult,
            $result->toString()
        );
    }

    /** @group doMe */
    public function test_it_replaces_route_placeholders_with_given_params_if_they_match(): void
    {
        $route = '/document/{id}/download/{format}';
        $params = ['id' => 13, 'format' => 'pdf'];
        $expectedResult = '/document/13/download/pdf';

        $result = (new UrlGenerator())->generate($route, $params);

        $this->assertSame(
            $expectedResult,
            $result->toString()
        );
    }

    public function test_given_params_with_no_matching_placeholder_in_route_become_part_of_query_string(): void
    {
        $route = '/document/{id}/show';
        $params = ['id' => '13', 'format' => 'pdf'];
        $expectedResult = '/document/13/show?format=pdf';

        $result = (new UrlGenerator())->generate($route, $params);

        $this->assertSame(
            $expectedResult,
            $result->toString()
        );
    }

    public function test_route_placeholders_with_no_match_in_given_params_are_not_replaced_with_anything(): void
    {
        $route = '/document/{id}/download/{format}';
        $params = ['format' => 'pdf'];
        $expectedResult = '/document/{id}/download/pdf';

        $result = (new UrlGenerator())->generate($route, $params);

        $this->assertSame(
            $expectedResult,
            $result->toString()
        );
    }

    public function test_placeholders_with_specifications_are_correctly_replaced_with_given_params(): void
    {
        $route = '/document/{id:\d+}/download/{format:doc|pdf|csv}';
        $params = ['id' => '13', 'format' => 'pdf'];
        $expectedResult = '/document/13/download/pdf';

        $result = (new UrlGenerator())->generate($route, $params);

        $this->assertSame(
            $expectedResult,
            $result->toString()
        );
    }

    public function test_params_which_only_partially_match_route_placeholder_do_not_replace_them(): void
    {
        $route = '/document/format/{name}';
        $params = ['nam' => 'pdf'];
        $expectedResult = '/document/format/{name}?nam=pdf';

        $result = (new UrlGenerator())->generate($route, $params);

        $this->assertSame(
            $expectedResult,
            $result->toString()
        );
    }

    public function test_query_params_in_returned_object_only_contain_params_which_did_not_match_route_named_parameters(): void
    {
        $route = '/document/format/{name}';
        $params = ['name' => 'pdf', 'foo' => 'bar'];
        $expectedResult = ['foo' => 'bar'];

        $result = (new UrlGenerator())->generate($route, $params);

        $this->assertSame(
            $expectedResult,
            $result->getQueryParams()
        );
    }
}
