<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PublicContentVisitedRepository")
 */
class PublicContentVisited
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countHomePage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countContentPage;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->countHomePage     = 0;
        $this->countContentPage  = 0;        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCountHomePage(): ?int
    {
        return $this->countHomePage;
    }

    public function setCountHomePage(?int $countHomePage): self
    {
        $this->countHomePage = $countHomePage;

        return $this;
    }

    public function getCountContentPage(): ?int
    {
        return $this->countContentPage;
    }

    public function setCountContentPage(?int $countContentPage): self
    {
        $this->countContentPage = $countContentPage;

        return $this;
    }
}
