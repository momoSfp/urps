<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Cette adresse email est déja utilisé, merci de la modifier")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="vous devez renseigner votre prénom") 
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Le prénom doit comporter au moins {{ limit }} caractères.",
     *      maxMessage = "Le prénom ne doit pas comporter plus de {{ limit }} caractères."
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="vous devez renseigner votre nom de famille") 
     * @Assert\Length(
     *      min = "2",
     *      max = "70",
     *      minMessage = "Le nom de famille doit comporter au moins {{ limit }} caractères.",
     *      maxMessage = "Le nom de famille ne doit pas comporter plus de {{ limit }} caractères."
     * ) 
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="vous devez renseigner votre email") 
     * @Assert\Email(message="Veuillez renseigner un email valide !") 
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="vous devez renseigner un mot de passe") 
     * @Assert\Length(
     *      min = "6",
     *      max = "70",
     *      minMessage = "Le mot de passe doit comporter au moins {{ limit }} caractères.",
     *      maxMessage = "Le mot de passe ne doit pas comporter plus de {{ limit }} caractères."
     * ) 
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vous n'avez pas correctement confirmé votre mot de passe !") 
     */
    private $passwordConfirm;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ParticipateContent", mappedBy="user")
     */
    private $participateContents;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tutor", inversedBy="users")
     */
    private $tutor;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Tutor", mappedBy="userRelation", cascade={"persist", "remove"})
     */
    private $tutorRelation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Content", inversedBy="recommendForUsers")
     */
    private $recommendedContent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $resetToken;

    public function __construct()
    {
        $this->participateContents = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->active = true;
        $this->userRoles = new ArrayCollection();
        $this->recommendedContent = new ArrayCollection();
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
        $roles = $this->userRoles->toArray();

        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

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
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm)
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFullname()
    {
        return $this->firstname . " " . $this->lastname;
    }

    public function getTutorFullname()
    {
        return "Dr " . $this->firstname . " " . $this->lastname . " (" . $this->tutorRelation->getPostcode() . ")" ;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|ParticipateContent[]
     */
    public function getParticipateContents(): Collection
    {
        return $this->participateContents;
    }

    public function addParticipateContent(ParticipateContent $participateContent): self
    {
        if (!$this->participateContents->contains($participateContent)) {
            $this->participateContents[] = $participateContent;
            $participateContent->setUser($this);
        }

        return $this;
    }

    public function removeParticipateContent(ParticipateContent $participateContent): self
    {
        if ($this->participateContents->contains($participateContent)) {
            $this->participateContents->removeElement($participateContent);
            // set the owning side to null (unless already changed)
            if ($participateContent->getUser() === $this) {
                $participateContent->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    public function getTutor(): ?Tutor
    {
        return $this->tutor;
    }

    public function setTutor(?Tutor $tutor): self
    {
        $this->tutor = $tutor;

        return $this;
    }

    public function getTutorRelation(): ?Tutor
    {
        return $this->tutorRelation;
    }

    public function setTutorRelation(Tutor $tutorRelation): self
    {
        $this->tutorRelation = $tutorRelation;

        // set the owning side of the relation if necessary
        if ($this !== $tutorRelation->getUserRelation()) {
            $tutorRelation->setUserRelation($this);
        }

        return $this;
    }

    /**
     * @return Collection|Content[]
     */
    public function getRecommendedContent(): Collection
    {
        return $this->recommendedContent;
    }

    public function addRecommendedContent(Content $recommendedContent): self
    {
        if (!$this->recommendedContent->contains($recommendedContent)) {
            $this->recommendedContent[] = $recommendedContent;
        }

        return $this;
    }

    public function removeRecommendedContent(Content $recommendedContent): self
    {
        if ($this->recommendedContent->contains($recommendedContent)) {
            $this->recommendedContent->removeElement($recommendedContent);
        }

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}
