<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CardRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=CardRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @ApiResource(
 *      attributes={
 *          "pagination_enabled"=false,
 *      },
 *      denormalizationContext={
 *          "groups"={"post:Card"},
 *      },
 *      normalizationContext={
 *          "groups"={"read:Card"},
 *      },
 *      collectionOperations={
 *          "post"={
 *              "security_post_denormalize"="is_granted('API', object)",
 *          },
 *          "get",
 *      },
 *      itemOperations={
 *          "get",
 *          "delete"={
 *              "security_post_denormalize"="is_granted('API', object)",
 *          },
 *          "patch"={
 *              "security_post_denormalize"="is_granted('API', object)",
 *          },
 *      },
 * )
 * @ApiFilter(SearchFilter::class, properties={"cardsList.id"="exact"})
 * @ApiFilter(OrderFilter::class, properties={"currentBoxNumber"="ASC", "movedAt"="ASC"})
 */
class Card
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read:Card", "read:CardsList"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"post:Card", "read:Card", "read:CardsList"})
     */
    private $frontValue;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"post:Card", "read:Card", "read:CardsList"})
     */
    private $backValue;

    /**
     * @ORM\ManyToOne(targetEntity=CardsList::class, inversedBy="cards")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post:Card", "read:Card"})
     */
    private $cardsList;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"post:Card", "read:Card", "read:CardsList"})
     */
    private $side = 'front';

    /**
     * @ORM\Column(type="integer")
     * @Groups({"post:Card", "read:Card", "read:CardsList"})
     */
    private $currentBoxNumber = 1;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"read:Card", "read:CardsList"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"read:Card", "read:CardsList"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Groups({"read:Card", "read:CardsList", "post:Card"})
     */
    private $movedAt;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read:Card", "read:CardsList", "post:Card"})
     */
    private $isStudiedInCurrentCycle = false;

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

    public function getIsStudiedInCurrentCycle(): ?bool
    {
        return $this->isStudiedInCurrentCycle;
    }

    public function setIsStudiedInCurrentCycle(bool $isStudiedInCurrentCycle): self
    {
        $this->isStudiedInCurrentCycle = $isStudiedInCurrentCycle;

        return $this;
    }
}
