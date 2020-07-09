<?php declare(strict_types=1);
namespace App\Http\Request;

use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

abstract class AbstractRequest
{
    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->request
            ->get($key, $default);
    }

    public function getSession(): SessionInterface
    {
        return $this->request
            ->getSession();
    }

    public function getFiles(): FileBag
    {
        return $this->request->files;
    }

    public function getClientIp(): ?string
    {
        return $this->request
            ->getClientIp();
    }

    public function getBasePath(): string
    {
        return $this->request->getSchemeAndHttpHost() . $this->request->getBasePath();
    }
}