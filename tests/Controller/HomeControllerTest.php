<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Welcome to MemOlicard');
    }

    public function testHomePageIfUserIsLogin(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy([
            "username" => "johndoe",
        ]);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/');

        $this->assertResponseRedirects('/dashboard');
    }
}
