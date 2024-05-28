<?php

namespace App\Entity;

use App\Repository\UserRoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
class UserRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'role', targetEntity: User::class)]
    private Collection $users;

    public function __construct() {
        $this->$users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
        public function getUsers(): Collection
        {
            return $this->users;
        }
    
        public function addUser(User $user): static
        {
            if (!$this->users->contains($user)) {
                $this->users->add($user);
                $user->setRole($this);
            }
            return $this;
        }
    
        public function removeUser(User $user): static
        {
            if ($this->users->removeElement($user)) {
                if ($user->getRole() === $this) {
                    $user->setRole(null);
                }
            }
            return $this;
        }
    
}
