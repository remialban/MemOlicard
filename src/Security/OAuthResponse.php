<?php

namespace App\Security;

class OAuthResponse {
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $id)
    {
        
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
