<?php

declare(strict_types=1);

class Session {
    private bool $isLocal;
    private string $domain;

    public function __construct(bool $isLocal = true, string $domain = "")
    {
        $this->isLocal = $isLocal;
        $this->domain = $domain;
    }

    public function initiateSessionDetails(int $lifetime = (60 * 60 * 24), string $path = "/", 
    string $sameSite = "None"): void
    {
        session_set_cookie_params([
            'lifetime' => $lifetime, 
            'path' => $path,
            'domain' => $this->domain, 
            'secure' => false, 
            'httponly' => true, 
            'samesite' => $sameSite,
        ]);
    }

    public function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function destroySession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }

    public function unsetSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
        }
    }

    public function regenerateSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public function checkSessionSecurity(): void
    {
        if (!isset($_SESSION['ip_address'])) {
            $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'];
        } elseif ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR']) {
            $this->destroySession();
        }
    }

    public function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    public function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function setFlash(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
    }

    public function getFlash(string $key): ?string
    {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]); 
            return $message;
        }
        return null;
    }
}
