<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Since;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @ExclusionPolicy("all")
 * @Hateoas\Relation(
 *      "self",
 *      href = "expr('/brands/' ~ object.getSlug())"
 * )
 * @Hateoas\Relation(
 *     "products",
 *     embedded = @Hateoas\Embedded("expr(object.getProducts())")
 * )
 */
class Brand
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
     * @Expose
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Expose
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="brand")
     */
    private $products;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTimeInterface
     */
    private $updated_at;

    /**
     * @var File|null
     * @Assert\File(
     *      maxSize = 2000000,
     *      maxSizeMessage = "Le fichier est trop volumineux (> 2Mo)"
     * )
     * @Assert\Image(
     *      mimeTypes = {"image/png", "image/gif", "image/jpeg"},
     *      mimeTypesMessage = "Votre image doit être de type png, gif ou jpeg"
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $slug;

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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if (null !== $imageFile) {
            $this->updated_at = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        }
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}
