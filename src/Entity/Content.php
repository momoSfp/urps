<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContentRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"title"},
 *  message="Un autre serious game possède déja ce titre")
 * @Vich\Uploadable
 */
class Content
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(min=5, max=200, minMessage="Le titre doit contenir plus de 5 caractères !",
     * maxMessage="Le titre ne peut pas contenir plus de 200 caractères !")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(min=5, max=4000, minMessage="la description du jeux doit contenir plus de 100 caractères !",
     * maxMessage="Une description ne peut pas contenir plus de 4000 caractères !")     
     */
    private $description;

    /**
     * @var File
     * @Vich\UploadableField(mapping="content_coverImage", fileNameProperty="coverImage")
     * @Assert\NotNull
     * @Assert\File(
     *   maxSize = "10M",
     *   maxSizeMessage = "Le fichier est trop volumineux. Sa taille ne doit pas dépasser {{size}} {{ suffix }}.",
     *   mimeTypes = {"image/png", "image/jpeg", "image/jpg", "image/gif"},
     *   mimeTypesMessage = "Ce type de fichier n'est pas autorisé. Seulement les fichiers images (gif, png, jpg) sont autorisé."
     * )
     */
    private $coverImageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $coverImage;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="boolean")
     */
    private $public;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $link;

    /**
     * @var File
     * @Vich\UploadableField(mapping="content_game", fileNameProperty="filename")     
     * @Assert\NotNull
     * @Assert\File(
     *   maxSize = "100M",
     *   maxSizeMessage = "Le fichier est trop volumineux. Sa taille ne doit pas dépasser {{size}} {{ suffix }}.",
     *   mimeTypes = {"application/gzip", "application/zip"},
     *   mimeTypesMessage = "Ce type de fichier n'est pas autorisé. Seulement les fichiers zip sont autorisé."
     * )
     */
    private $gameFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $fileName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastUpdateAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="content", orphanRemoval=true)
     * @Assert\Valid()
     */
    private $images;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ParticipateContent", mappedBy="content", orphanRemoval=true)
     */
    private $participateContents;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $question;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="recommendedContent")
     */
    private $recommendForUsers;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->participateContents = new ArrayCollection();
        $this->recommendForUsers = new ArrayCollection();
    }

    /**
     * Get slug of title
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function initializeSlug()
    {
        if (empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->title);
        }
    }

    /**
     * init lastUpdateAt
     *
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function initLastUpdate()
    {
        $this->lastUpdateAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage($coverImage): self
    {
        $this->coverImage = $coverImage;

        return $this;
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

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): self
    {
        $this->public = $public;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

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

    public function getLastUpdateAt(): ?\DateTimeInterface
    {
        return $this->lastUpdateAt;
    }

    public function setLastUpdateAt(\DateTimeInterface $lastUpdateAt): self
    {
        $this->lastUpdateAt = $lastUpdateAt;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setContent($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getContent() === $this) {
                $image->setContent(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setCoverImageFile(?File $coverImageFile = null): void
    {
        $this->coverImageFile = $coverImageFile;

        if (null !== $coverImageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->lastUpdateAt = new \DateTime('now');
        }        
    }

    public function getCoverImageFile(): ?File
    {
        return $this->coverImageFile;
    }

    public function setGameFile(?File $gameFile = null): void
    {
        $this->gameFile = $gameFile;

        if (null !== $gameFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->lastUpdateAt = new \DateTime('now');
        }        
    }

    public function getGameFile(): ?File
    {
        return $this->gameFile;
    }

    public function setFileName(?string $fileName): void
    {
        $this->fileName = $fileName;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
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
            $participateContent->setContent($this);
        }

        return $this;
    }

    public function removeParticipateContent(ParticipateContent $participateContent): self
    {
        if ($this->participateContents->contains($participateContent)) {
            $this->participateContents->removeElement($participateContent);
            // set the owning side to null (unless already changed)
            if ($participateContent->getContent() === $this) {
                $participateContent->setContent(null);
            }
        }

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRecommendForUsers(): Collection
    {
        return $this->recommendForUsers;
    }

    public function addRecommendForUser(User $recommendForUser): self
    {
        if (!$this->recommendForUsers->contains($recommendForUser)) {
            $this->recommendForUsers[] = $recommendForUser;
            $recommendForUser->addRecommendedContent($this);
        }

        return $this;
    }

    public function removeRecommendForUser(User $recommendForUser): self
    {
        if ($this->recommendForUsers->contains($recommendForUser)) {
            $this->recommendForUsers->removeElement($recommendForUser);
            $recommendForUser->removeRecommendedContent($this);
        }

        return $this;
    }

}
