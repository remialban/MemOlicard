<?php

namespace App\Security;

class OAuthResponse {
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $id;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $id)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->id = $id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
