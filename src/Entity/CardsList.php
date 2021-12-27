<?php

namespace App\Entity;

use App\Repository\CardsListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardsListRepository::class)]
class CardsList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'cardsList')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private $updateAt;

    #[ORM\Column(type: 'integer')]
    private $boxesNumber;

    #[ORM\Column(type: 'integer')]
    private $currentCycleNumber;

    #[ORM\OneToMany(mappedBy: 'cardsList', targetEntity: Card::class)]
    private $cards;

    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getBoxesNumber(): ?int
    {
        return $this->boxesNumber;
    }

    public function setBoxesNumber(int $boxesNumber): self
    {
        $this->boxesNumber = $boxesNumber;

        return $this;
    }

    public function getCurrentCycleNumber(): ?int
    {
        return $this->currentCycleNumber;
    }

    public function setCurrentCycleNumber(int $currentCycleNumber): self
    {
        $this->currentCycleNumber = $currentCycleNumber;

        return $this;
    }

    /**
     * @return Collection|Card[]
     */
    public function getCards(): Collection
    {
        return $this->cards;
    }

    public function addCard(Card $card): self
    {
        if (!$this->cards->contains($card)) {
            $this->cards[] = $card;
            $card->setCardsList($this);
        }

        return $this;
    }

    public function removeCard(Card $card): self
    {
        if ($this->cards->removeElement($card)) {
            // set the owning side to null (unless already changed)
            if ($card->getCardsList() === $this) {
                $card->setCardsList(null);
            }
        }

        return $this;
    }
}
