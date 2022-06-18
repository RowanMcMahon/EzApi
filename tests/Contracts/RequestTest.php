<?php

use EzApi\Contracts\Request;
use PHPUnit\Framework\TestCase;

final class RequestTest extends TestCase
{
    public function test_empty_data_on_get_wont_append_question_mark(): void
    {
        $r = new Request('GET', 'example.com');
        $this->assertEquals('https://example.com', $r->getUrl());
    }

    public function test_constructs_query_string_for_get_requests(): void
    {
        $r = new Request('GET', 'example.com', 'test', ['foo' => 'bar']);
        $this->assertEquals('https://example.com/test?foo=bar', $r->getUrl());
    }

    public function test_allows_trailing_slashes_on_host(): void
    {
        $r = new Request('GET', 'example.com/');
        $this->assertEquals('https://example.com', $r->getUrl());
    }

    public function test_allows_trailing_slashes_on_endpoint(): void
    {
        $r = new Request('GET', 'example.com', '/test/');
        $this->assertEquals('https://example.com/test', $r->getUrl());
    }

    public function test_sets_ssl_scheme_by_default(): void
    {
        $r = new Request('GET', 'example.com');
        $this->assertEquals('https://example.com', $r->getUrl());
    }

    public function test_only_allows_valid_ssl_options(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $r = new Request('GET', 'example.com', 'test', [], null, 'foo');
    }

    public function test_sets_non_ssl_scheme_when_specified(): void
    {
        $r = new Request('GET', 'example.com', 'test', [], null, 'http');
        $this->assertEquals('http://example.com/test', $r->getUrl());
    }
}
