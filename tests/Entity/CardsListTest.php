<?php

namespace App\Tests;

use App\Entity\Card;
use App\Entity\User;
use App\Entity\CardsList;
use PHPUnit\Framework\TestCase;

class CardsListTest extends TestCase
{
    public function testIsTrue()
    {
        $createdAt = new \DateTimeImmutable();
        $updatedAt = new \DateTimeImmutable();

        $user = new User();

        $card1 = new Card();
        $card2 = new Card();

        $list = (new CardsList())
            ->setName("Story")
            ->setCurrentCycle(854)
            ->setBoxesNumber(7)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt)
            ->setUser($user)
            ->addCard($card1)
            ->addCard($card2)
        ;

        $this->assertEquals("Story", $list->getName());
        $this->assertEquals(854, $list->getCurrentCycle());
        $this->assertEquals(7, $list->getBoxesNumber());
        $this->assertEquals($createdAt, $list->getCreatedAt());
        $this->assertEquals($updatedAt, $list->getUpdatedAt());
        $this->assertEquals($user, $list->getUser());
        $this->assertEquals([$card1, $card2], $list->getCards()->toArray());
        $this->assertEquals($list, $list->removeCard($card1));
        $this->assertNull($list->getId());
    }

    public function testIsFalse()
    {
        $list = new CardsList();
        $this->assertEquals(1, $list->getCurrentCycle());
        $this->assertEquals(3, $list->getBoxesNumber());
    }
}
