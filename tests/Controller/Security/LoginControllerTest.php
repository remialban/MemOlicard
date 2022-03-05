<?php

namespace App\Tests\Controller;

use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testLoginPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Log in');
    }

    public function testLoginPageIfUserIsLogin(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy([
            "username" => "johndoe",
        ]);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/login');

        $this->assertResponseRedirects('/dashboard');
    }

    public function testSuccessfullLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('Log in');

        $form = $buttonCrawlerNode->form();

        $form['login[username]'] = 'johndoe@johndoe.com';
        $form['login[password]'] = 'John-Doe12';

        $client->submit($form);

        $this->assertResponseRedirects('/dashboard');
        $client->followRedirect();
        $this->assertSelectorTextContains(".toast-body", "Welcome John!");
    }

    public function testFailureLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('Log in');

        $form = $buttonCrawlerNode->form();

        $form['login[username]'] = 'incorect username';
        $form['login[password]'] = 'password xD';

        $client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(".toast-body", "Your email or username is incorrect.");
    }
}
