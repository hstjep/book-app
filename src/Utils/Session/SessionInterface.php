<?php

namespace App\Utils\Session;

interface SessionInterface
{
    /**
     * Gets the value from session by key
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Sets the value to session
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void;
}
