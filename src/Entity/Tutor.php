<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TutorRepository")
 */
class Tutor
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $postcode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="tutor")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="tutorRelation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $userRelation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adeli;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $plainTextPass;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): self
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setTutor($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getTutor() === $this) {
                $user->setTutor(null);
            }
        }

        return $this;
    }

    public function getUserRelation(): ?User
    {
        return $this->userRelation;
    }

    public function setUserRelation(User $userRelation): self
    {
        $this->userRelation = $userRelation;

        return $this;
    }

    public function getAdeli(): ?string
    {
        return $this->adeli;
    }

    public function setAdeli(?string $adeli): self
    {
        $this->adeli = $adeli;

        return $this;
    }

    public function getPlainTextPass(): ?string
    {
        return $this->plainTextPass;
    }

    public function setPlainTextPass(string $plainTextPass): self
    {
        $this->plainTextPass = $plainTextPass;

        return $this;
    }
}
