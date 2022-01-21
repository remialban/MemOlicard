<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CardRepository;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $frontValue;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $backValue;

    #[ORM\ManyToOne(targetEntity: CardsList::class, inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    private $cardsList;

    #[ORM\Column(type: 'string', length: 255)]
    private $side = 'front';

    #[ORM\Column(type: 'integer')]
    private $currentBoxNumber = 1;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $createdAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private $movedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrontValue(): ?string
    {
        return $this->frontValue;
    }

    public function setFrontValue(string $frontValue): self
    {
        $this->frontValue = $frontValue;

        return $this;
    }

    public function getBackValue(): ?string
    {
        return $this->backValue;
    }

    public function setBackValue(?string $backValue): self
    {
        $this->backValue = $backValue;

        return $this;
    }

    public function getCardsList(): ?CardsList
    {
        return $this->cardsList;
    }

    public function setCardsList(?CardsList $cardsList): self
    {
        $this->cardsList = $cardsList;

        return $this;
    }

    public function getSide(): ?string
    {
        return $this->side;
    }

    public function setSide(string $side): self
    {
        $this->side = $side;

        return $this;
    }

    public function getCurrentBoxNumber(): ?int
    {
        return $this->currentBoxNumber;
    }

    public function setCurrentBoxNumber(int $currentBoxNumber): self
    {
        $this->currentBoxNumber = $currentBoxNumber;

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

    public function getMovedAt(): ?\DateTimeImmutable
    {
        return $this->movedAt;
    }

    public function setMovedAt(\DateTimeImmutable $movedAt): self
    {
        $this->movedAt = $movedAt;

        return $this;
    }
    
    /**
     * Gets triggered only on insert

     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $dateTimeImmutable = new \DateTimeImmutable();
        $this->createdAt = $dateTimeImmutable;
        $this->updatedAt = $dateTimeImmutable;
        $this->movedAt = $dateTimeImmutable;
    }

    /**
     * Gets triggered every time on update

     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTimeImmutable("now");
    }
}
