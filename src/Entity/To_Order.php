<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`to_order`')]
class Order
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: user::class, inversedBy: 'orders')]
    private Collection $User;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: product::class, inversedBy: 'orders')]
    private Collection $Product;

    #[ORM\Column()]
    private Boolean $isValidated;

    public function __construct()
    {
        $this->User = new ArrayCollection();
        $this->Product = new ArrayCollection();
    }
    /**
     * @return Collection<int, user>
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(user $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User->add($user);
        }

        return $this;
    }

    public function removeUser(user $user): self
    {
        $this->User->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, product>
     */
    public function getProduct(): Collection
    {
        return $this->Product;
    }

    public function addProduct(product $product): self
    {
        if (!$this->Product->contains($product)) {
            $this->Product->add($product);
        }

        return $this;
    }

    public function removeProduct(product $product): self
    {
        $this->Product->removeElement($product);

        return $this;
    }

    public function getIsValidated(): Boolean
    {
        return $this->isValidated;
    }

    public function setIsValidated(string $isValidated): self
    {
        $this->isValidated = $isValidated;

        return $this;
    }
}
