<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *      message = "Champ requis",
     * )
     * @Assert\Length(
     *      min = 2,
     *      max = 20,
     *      minMessage = "Le nom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le nom doit comporter au maximum {{ limit }} caractères",
     *      allowEmptyString = false
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *      message = "Champ requis",
     * )
     * @Assert\PositiveOrZero(
     *      message = "Le nombre saisi doit être nul ou positif",
     * )
     */
    private $hierarchy_order;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="category")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHierarchyOrder(): ?int
    {
        return $this->hierarchy_order;
    }

    public function setHierarchyOrder(int $hierarchy_order): self
    {
        $this->hierarchy_order = $hierarchy_order;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeCategory($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}