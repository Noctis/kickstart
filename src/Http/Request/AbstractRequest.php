<?php declare(strict_types=1);
namespace App\Http\Request;

use Darsyn\IP\Version\IPv4;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractRequest extends Request
{
    /**
     * @psalm-suppress ImplementedReturnTypeMismatch
     */
    public function getClientIP(): IPv4
    {
        /** @psalm-suppress PossiblyNullArgument */
        return IPv4::factory(
            parent::getClientIp()
        );
    }

    public function getSessionID(): string
    {
        return $this->getSession()
            ->getId();
    }
}