<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class PasswordResetEvent extends Event
{
    public function __construct(private string $lastname, private string $firstname, private string $email)
    {
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
