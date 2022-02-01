<?php

namespace App\Security\OAuth;

use App\Security\OAuthResponse;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Google
{
    public function __construct(private HttpClientInterface $httpClient)
    {
        
    }

    public static function getLoginPageUrl()
    {
        return "https://accounts.google.com/o/oauth2/v2/auth?client_id=" . $_ENV['OAUTH_ID_GOOGLE'] . "&redirect_uri=" . urlencode("http://localhost:8000/login?service=google") . "&response_type=code&scope=openid%20profile%20email";
    }

    public function getCredentials(Request $request): ?OAuthResponse
    {
        try {
            $response = $this->httpClient->request('POST', 'https://oauth2.googleapis.com/token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Content-Length' => 0,
                    'Accept' => '*/*',
                    //'Connection' => 'keep-alice',
                ],
                'query' => [
                    'code' => $request->get('code'),
                    'client_id' => $_ENV['OAUTH_ID_GOOGLE'],
                    'client_secret' => $_ENV['OAUTH_SECRET_KEY_GOOGLE'],
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => 'http://localhost:8000/login?service=google',
                ],
            ]);
            
            $responseArray = json_decode($response->getContent(), true);
            $token = $responseArray['id_token'];
            $responseValidateToken = $this->httpClient->request('GET', 'https://oauth2.googleapis.com/tokeninfo', [
                'query' => [
                    'id_token' => $token,
                ]
            ]);
            $responseValidateTokenArray = json_decode($responseValidateToken->getContent(), true);
            return new OAuthResponse(
                $responseValidateTokenArray['given_name'],
                $responseValidateTokenArray['family_name'],
                $responseValidateTokenArray['email'],
                $responseValidateTokenArray['sub']
            );
        } catch (ClientException $clientException) {
        }
        
        return null;
    }
}
