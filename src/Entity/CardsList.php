<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CardsListRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CardsListRepository::class)
 * @ApiResource(
 *      attributes={
 *          "pagination_enabled"=false,
 *      },
 *      denormalizationContext={
 *          "groups"={"write:CardsList"},
 *      },
 *      normalizationContext={
 *          "groups"={"read:CardsList"},
 *      },
 *      collectionOperations={},
 *      itemOperations={
 *          "get"={
 *              "security_post_denormalize"="is_granted('API', object)",
 *          },
 *          "patch"={
 *              "security_post_denormalize"="is_granted('API', object)",
 *          },
 *      },
 * )
 */
class CardsList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:CardsList"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="cardsLists")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read:CardsList"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"read:CardsList"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Groups({"read:CardsList"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"read:CardsList"})
     */
    private $boxesNumber = 3;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"read:CardsList", "write:CardsList"})
     */
    private $name;

    /**
     * @ORM\OneToMany(mappedBy="cardsList", targetEntity=Card::class, orphanRemoval=true)
     * @Groups({"read:CardsList"})
     */
    private $cards;

    /**
     * @ORM\Column(type="bigint")
     * @Groups({"read:CardsList", "write:CardsList"})
     */
    private $currentCycle = 1;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getCurrentCycle(): ?string
    {
        return $this->currentCycle;
    }

    public function setCurrentCycle(string $currentCycle): self
    {
        $this->currentCycle = $currentCycle;

        return $this;
    }
}
