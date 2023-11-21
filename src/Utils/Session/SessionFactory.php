<?php

namespace App\Utils\Session;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionFactory
{
    private $requestStack;

    public function __construct(
        RequestStack $requestStack
    ) {
        $this->requestStack = $requestStack;
    }

    /**
     * Creates session instance
     *
     * @return SessionInterface
     */
    public function create(): SessionInterface
    {
        if ($this->requestStack->getCurrentRequest() !== null) {
            return new HttpSession($this->requestStack);
        } else {
            return ConsoleSession::getInstance();
        }
    }
}
