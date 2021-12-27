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

    #[ORM\Column(type: 'text')]
    private $frontValue;

    #[ORM\Column(type: 'text')]
    private $backValue;

    #[ORM\ManyToOne(targetEntity: CardsList::class, inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: false)]
    private $cardsList;

    #[ORM\Column(type: 'string', length: 255)]
    private $nextSide;

    #[ORM\Column(type: 'integer')]
    private $current_box_number;

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

    public function setBackValue(string $backValue): self
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

    public function getNextSide(): ?string
    {
        return $this->nextSide;
    }

    public function setNextSide(string $nextSide): self
    {
        $this->nextSide = $nextSide;

        return $this;
    }

    public function getCurrentBoxNumber(): ?int
    {
        return $this->current_box_number;
    }

    public function setCurrentBoxNumber(int $current_box_number): self
    {
        $this->current_box_number = $current_box_number;

        return $this;
    }
}
