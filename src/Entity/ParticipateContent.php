<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ParticipateContentRepository")
 */
class ParticipateContent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="participateContents")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Content", inversedBy="participateContents")
     */
    private $content;

    /**
     * @ORM\Column(type="json")
     */
    private $result = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $completedAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->duration  = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getContent(): ?Content
    {
        return $this->content;
    }

    public function setContent(?Content $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getResult(): ?array
    {
        return $this->result;
    }

    public function setResult(array $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getResultByContentIdAndUserId($UserId, $contentId)
    {
        if($UserId === $this->user->getId() && $contentId === $this->content->getId())
        {
            return $this->result;
        }
        else return false;
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

    public function getCompletedAt(): ?\DateTimeInterface
    {
        return $this->completedAt;
    }

    public function setCompletedAt(?\DateTimeInterface $completedAt): self
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }
}
