<?php

namespace App\Utils\Session;

use Symfony\Component\HttpFoundation\RequestStack;

class HttpSession implements SessionInterface
{
    private $requestStack;

    public function __construct(
        RequestStack $requestStack
    ) {
        $this->requestStack = $requestStack;
    }

    /**
     * Gets the value from session by key
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return $this->requestStack->getSession()->get($key);
    }

    /**
     * Sets the value to session
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->requestStack->getSession()->set($key, $value);
    }
}
