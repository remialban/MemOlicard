<?php

namespace App\Tests\Controller;

use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterControllerTest extends WebTestCase
{
    public function testRegisterPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signin');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Sign in');
    }

    public function testLoginPageIfUserIsLogin(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy([
            "username" => "johndoe",
        ]);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/signin');

        $this->assertResponseRedirects('/dashboard');
    }

    public function testSuccessfullRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signin');
        $buttonCrawlerNode = $crawler->selectButton('Log in');

        $form = $buttonCrawlerNode->form();

        $form['registration[firstName]'] = 'Jean';
        $form['registration[lastName]'] = 'DURAND';
        $form['registration[username]'] = 'jdurand';
        $form['registration[email]'] = 'jeandurand@xxxx.com';
        $form['registration[password]'] = 'JDURANT-12';
        $form['registration[confirmPassword]'] = 'JDURANT-12';

        $client->submit($form);

        $this->assertResponseRedirects('/login');
        $client->followRedirect();
        $this->assertSelectorTextContains(".toast-body", "Your registration has been validated you are now registered on our site. We have sent to you an email in order to confirm that you are the owner of this email address.");
    }

    public function testFailureRegister()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/signin');
        $buttonCrawlerNode = $crawler->selectButton('Log in');

        $form = $buttonCrawlerNode->form();

        $form['registration[firstName]'] = 'Jean';
        $form['registration[lastName]'] = 'DURAND';
        $form['registration[username]'] = 'johndoe';
        $form['registration[email]'] = 'jeandurand@xxxx.com';
        $form['registration[password]'] = 'JDURANT-12';
        $form['registration[confirmPassword]'] = 'JDURANT-12';

        $client->submit($form);

        $this->assertResponseIsSuccessful('/signin');
        $this->assertSelectorExists("invalid-feedback d-block");
    }
}
