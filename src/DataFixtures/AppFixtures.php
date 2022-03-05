<?php

namespace App\DataFixtures;

use App\Entity\Card;
use App\Entity\CardsList;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        $user = (new User())
            ->setFirstName("John")
            ->setLastName("Doe")
            ->setBiography("Students")
            ->setEmail("johndoe@johndoe.com")
            ->setUsername("johndoe")
            ->setEmailIsChecked(true);
        ;
        $user->setPassword($this->hasher->hashPassword($user, "John-Doe12"));

        $manager->persist($user);

        $users = [];

        for ($i=0; $i < 20; $i++) { 
            $user = (new User())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setBiography($faker->realText())
                ->setEmail($faker->email())
                ->setUsername($faker->userName())
                ->setEmailIsChecked($faker->boolean());
            ;
            $user->setPassword($this->hasher->hashPassword($user, "password123"));

            $users[] = $user;
            $manager->persist($user);
        }

        $lists = [];

        for ($i=0; $i < 100; $i++) {
            $list = (new CardsList())
                ->setName($faker->realText(50))
                ->setUser($faker->randomElement($users))
                ->setBoxesNumber($faker->numberBetween(2,10))
                ->setCurrentCycle($faker->numberBetween(1, 1000))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
            ;

            $lists[] = $list;

            $manager->persist($list);
        }

        for ($i=0; $i < 1000; $i++) { 
            $card = (new Card())
                ->setCardsList($faker->randomElement($lists))
                ->setFrontValue($faker->word())
                ->setBackValue($faker->word())
                ->setMovedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setSide($faker->randomElement(["front", "back"]))
                ->setIsStudiedInCurrentCycle($faker->boolean())
            ;
            $manager->persist($card);
        }
        $manager->flush();
    }
}
