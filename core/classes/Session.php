<?php

namespace Core;

use App\App;

class Session
{
    private ?array $user = null;

    /**
     * Session constructor
     */
    public function __construct()
    {
        session_start();
        $this->loginFromCookie();
    }

    /**
     * Login user from $_SESSION
     *
     * @return bool
     */
    public function loginFromCookie(): bool
    {
        if ($_SESSION) {
            return $this->login($_SESSION['email'], $_SESSION['password']);
        }

        return false;
    }

    /**
     * Login user
     *
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function login(string $email, string $password): bool
    {
        $user = App::$db->getRowWhere('users', [
            'email' => $email,
            'password' => $password,
        ]);

        if ($user) {
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $password;
            $this->user = $user;

            return true;
        }

        $this->user = null;

        return false;
    }

    /**
     * Getter for private array $user
     *
     * @return array|null
     */
    public function getUser(): ?array
    {
        return $this->user;
    }

    /**
     * Logout user and redirect to another location
     *
     * @param string|null $redirect
     */
    public function logout(?string $redirect = null): void
    {
        $_SESSION = [];
        session_destroy();

        if ($redirect) {
            header("Location: $redirect");
        }
    }
}