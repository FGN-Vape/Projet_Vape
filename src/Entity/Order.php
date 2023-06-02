<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'to_order')]
class Order
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    private User $user;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'orders')]
    private Product $product;

    #[ORM\Column(type: 'boolean')]
    private bool $isValidated;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column (nullable: true)]
    private ?\DateTimeImmutable $OrderedAt = null;

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getIsValidated(): bool
    {
        return $this->isValidated;
    }

    public function setIsValidated(bool $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
    public function getPrice(): float
    {
        return $this->product->getPrice();
    }

    public function getOrderedAt(): ?\DateTimeImmutable
    {
        return $this->OrderedAt;
    }

    public function setOrderedAt(\DateTimeImmutable $OrderedAt): self
    {
        $this->OrderedAt = $OrderedAt;

        return $this;
    }
}
