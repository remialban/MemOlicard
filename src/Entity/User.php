<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(
    fields: ['email', 'username'],
    message: '{{ label }} is already used',
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(
        message: "The email is required"
    )]
    #[Assert\Email]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string', nullable: true)]
    #[Assert\NotBlank(
        message: "The password cannot be empty",
        groups: ['security_password'],
    )]
    #[Assert\Length(
        min: 8,
        max: 30,
        minMessage: "The password size must be between 8 and 30 characters",
    )]
    private $password;

    #[Assert\NotBlank(
        message: "The password cannot be empty",
        groups: ['security_password'],
    )]
    #[Assert\Length(
        min: 8,
        max: 30,
        minMessage: "The password size must be between 8 and 30 characters",
        groups: ['security_password'],
    )]
    public $modifiedPassword;

    #[Assert\EqualTo(
        propertyPath: "modifiedPassword",
        message: "Both passwords must the same",
        groups: ['security_password'],
    )]
    private $confirmPassword;

    #[SecurityAssert\UserPassword(
        message: "Your current password is incorrect",
        groups: ['security_password'],        
    )]
    protected $oldPassword;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: "The first name is required",
        groups: ['profile'],
    )]
    private $firstName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: "The last name is required",
        groups: ['profile'],
    )]
    private $lastName;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(
        message: "The username is required",
    )]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        htmlPattern: "^[a-zA-Z]+$",
        message: "The username must contain only letters and/or numbers. The special characters are not allowed."
    )]
    private $username;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CardsList::class)]
    private $cardsLists;

    #[ORM\Column(type: 'text', nullable: true)]
    private $biography;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $googleId;

    public function __construct()
    {
        $this->cardsLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getConfirmPassword(): string
    {
        return $this->password;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection|CardsList[]
     */
    public function getCardsLists(): Collection
    {
        return $this->cardsLists;
    }

    public function addCardsList(CardsList $cardsList): self
    {
        if (!$this->cardsLists->contains($cardsList)) {
            $this->cardsLists[] = $cardsList;
            $cardsList->setUser($this);
        }

        return $this;
    }

    public function removeCardsList(CardsList $cardsList): self
    {
        if ($this->cardsLists->removeElement($cardsList)) {
            // set the owning side to null (unless already changed)
            if ($cardsList->getUser() === $this) {
                $cardsList->setUser(null);
            }
        }

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }
}
