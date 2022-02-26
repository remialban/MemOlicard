<?php

namespace App\Tool;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class CustomJWT
{
    private string $publicKey;

    private $privateKey;

    public function __construct($privateKeyPath)
    {
        $this->privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
        $this->publicKey = openssl_pkey_get_details($this->privateKey)["key"];     
    }

    public function generateToken($payload): string
    {
        return JWT::encode($payload, $this->privateKey, 'RS256');
    }

    public function getData(string $token)
    {
        $decoded = JWT::decode($token, new Key($this->publicKey, 'RS256'));        
        $decoded_array = (array) $decoded;
        return $decoded_array;
    }
}
