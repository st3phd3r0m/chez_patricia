<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(
     *      message = "Ce champ doit être rempli",
     * )
     * @Assert\Email(
     *      message = "Adresse e-mail invalide"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *      message = "Ce champ doit être rempli",
     * )
     * @Assert\Length(
     *      min = 1,
     *      max = 20,
     *      minMessage = "Votre prénom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Votre prénom doit comporter au maximum {{ limit }} caractères",
     *      allowEmptyString = false
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *      message = "Ce champ doit être rempli",
     * )
     * @Assert\Length(
     *      min = 1,
     *      max = 20,
     *      minMessage = "Votre nom doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Votre nom doit comporter au maximum {{ limit }} caractères",
     *      allowEmptyString = false
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     * @Assert\NotBlank(
     *      message = "Ce champ doit être rempli",
     * )
     * @Assert\Length(
     *      min = 10,
     *      max = 180,
     *      minMessage = "Votre adresse doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Votre adresse doit comporter au maximum {{ limit }} caractères",
     *      allowEmptyString = false
     * )
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\NotBlank(
     *      message = "Ce champ doit être rempli",
     * )
     * @Assert\Length(
     *      min = 5,
     *      max = 10,
     *      minMessage = "Votre code postal doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Votre code postal doit comporter au maximum {{ limit }} caractères",
     *      allowEmptyString = false
     * )
     */
    private $postal_code;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\NotBlank(
     *      message = "Ce champ doit être rempli",
     * )
     * @Assert\Length(
     *      min = 8,
     *      max = 15,
     *      minMessage = "Votre code postal doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Votre code postal doit comporter au maximum {{ limit }} caractères",
     *      allowEmptyString = false
     * )
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     */
    private $email_verified;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="customer")
     */
    private $comments;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): self
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getEmailVerified(): ?bool
    {
        return $this->email_verified;
    }

    public function setEmailVerified(bool $email_verified): self
    {
        $this->email_verified = $email_verified;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setCustomer($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCustomer() === $this) {
                $comment->setCustomer(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_CUSTOMER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->firstname .' '.$this->lastname;
    }
}
