<?php

namespace App\Tests;

use App\Entity\Card;
use App\Entity\CardsList;
use PHPUnit\Framework\TestCase;

class CardTest extends TestCase
{
    public function testIsTrue()
    {
        $list = new CardsList();

        $createdAt = new \DateTimeImmutable();
        $updatedAt = new \DateTimeImmutable();
        $movedAt = new \DateTimeImmutable();

        $card = new Card;
        $card->onPrePersist();
        $card->onPreUpdate();
        $card
            ->setFrontValue("Best website to learn list")
            ->setBackValue("MemOlicard")
            ->setSide("back")
            ->setCurrentBoxNumber(3)
            ->setCardsList($list)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt)
            ->setMovedAt($movedAt)
            ->setIsStudiedInCurrentCycle(true)
        ;

        $this->assertNull($card->getId());
        $this->assertEquals("Best website to learn list", $card->getFrontValue());
        $this->assertEquals("MemOlicard", $card->getBackValue());
        $this->assertEquals("back", $card->getSide());
        $this->assertEquals(3, $card->getCurrentBoxNumber());
        $this->assertEquals($list, $card->getCardsList());
        $this->assertEquals($createdAt, $card->getCreatedAt());
        $this->assertEquals($updatedAt, $card->getUpdatedAt());
        $this->assertEquals($movedAt, $card->getMovedAt());
        $this->assertTrue($card->getIsStudiedInCurrentCycle());
    }

    public function testIsFalse()
    {
        $card = new Card();

        $this->assertEquals("front", $card->getSide());
        $this->assertEquals(1, $card->getCurrentBoxNumber());
        $this->assertFalse($card->getIsStudiedInCurrentCycle());
    }
}
