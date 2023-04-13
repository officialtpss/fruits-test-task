<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 180)]
    private $username;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FavouriteFruit::class, orphanRemoval: true)]
    private Collection $favouriteFruits;

    public function __construct()
    {
        $this->favouriteFruits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsername(): string
    {
        return (string) $this->username;
    }
 
    public function setUsername(string $username): self
    {
        $this->username = $username;
 
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
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
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, FavouriteFruit>
     */
    public function getFavouriteFruits(): Collection
    {
        return $this->favouriteFruits;
    }

    public function addFavouriteFruit(FavouriteFruit $favouriteFruit): self
    {
        if (!$this->favouriteFruits->contains($favouriteFruit)) {
            $this->favouriteFruits->add($favouriteFruit);
            $favouriteFruit->setUser($this);
        }

        return $this;
    }

    public function removeFavouriteFruit(FavouriteFruit $favouriteFruit): self
    {
        if ($this->favouriteFruits->removeElement($favouriteFruit)) {
            // set the owning side to null (unless already changed)
            if ($favouriteFruit->getUser() === $this) {
                $favouriteFruit->setUser(null);
            }
        }

        return $this;
    }

    
}
