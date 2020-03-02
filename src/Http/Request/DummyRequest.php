<?php declare(strict_types=1);
namespace App\Http\Request;

/** @psalm-suppress PropertyNotSetInConstructor */
final class DummyRequest extends AbstractRequest
{
    public function getFoo(): string
    {
        return 'foo';
    }
}