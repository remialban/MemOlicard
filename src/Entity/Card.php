<?php

namespace App\Entity;

use App\Repository\CardRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CardRepository::class)]
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
    private $side;

    #[ORM\Column(type: 'integer')]
    private $currentBoxNumber;

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
}
