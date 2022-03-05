<?php

namespace App\Tests;

use App\Entity\CardsList;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIsTrue()
    {
        $list1 = new CardsList();
        $list2 = new CardsList();

        $user = (new User())
            ->setFirstName("John")
            ->setLastName("Doe")
            ->setEmail("johndoe@domain.com")
            ->setUsername("johndoe")
            ->setPassword("myBestCat")
            ->setConfirmPassword("myBestCat_confirm")
            ->setOldPassword("myBestCat_old")
            ->setModifiedPassword("myBestCat_modified")
            ->setRoles(["ROLE_ADMIN"])
            ->setEmailIsChecked(true)
            ->setBiography("My biography .......")
            ->setGoogleId("85859858458454")
            ->addCardsList($list1)
            ->addCardsList($list2)
        ;

        $this->assertNull($user->getId());

        $this->assertTrue($user->getFirstName() === "John");
        $this->assertTrue($user->getLastName() === "Doe");
        $this->assertTrue($user->getEmail() === "johndoe@domain.com");
        $this->assertTrue($user->getUsername() === "johndoe");
        $this->assertEquals("My biography .......", $user->getBiography());

        $this->assertTrue($user->getOldPassword() === "myBestCat_old");
        $this->assertTrue($user->getPassword() === "myBestCat");
        $this->assertTrue($user->getConfirmPassword() === "myBestCat_confirm");
        $this->assertTrue($user->getModifiedPassword() === "myBestCat_modified");
    
        $this->assertNull($user->getSalt());

        $this->assertEquals($user->getEmail(), $user->getUserIdentifier());
        $this->assertEquals(["ROLE_ADMIN", "ROLE_USER"], $user->getRoles());

        $this->assertTrue($user->getEmailIsChecked());

        $this->assertEquals("85859858458454", $user->getGoogleId());
        
        $this->assertEquals([$list1, $list2], $user->getCardsLists()->toArray());
        $this->assertEquals($user, $user->removeCardsList($list1));
    }

    public function testIsFalse()
    {
        $user = new User();

        $this->assertNull($user->getId());

        $this->assertNull($user->getFirstName());
        $this->assertNull($user->getLastName());
        $this->assertNull($user->getEmail());
        $this->assertNull($user->getUsername());
        $this->assertNull($user->getBiography());

        $this->assertNull($user->getSalt());
        $this->assertEquals(["ROLE_USER"], $user->getRoles());

        $this->assertFalse($user->getEmailIsChecked());

        $this->assertNull($user->getGoogleId());

        $this->assertEquals([], $user->getCardsLists()->toArray());
    }
}
