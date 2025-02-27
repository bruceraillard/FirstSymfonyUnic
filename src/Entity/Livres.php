<?php

namespace App\Entity;

use App\Repository\LivresRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivresRepository::class)]
class Livres
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $publicationdate = null;

    #[ORM\Column(length: 255)]
    private ?string $publishinghouse = null;

    #[ORM\ManyToOne(inversedBy: 'livre')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Auteurs $auteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPublicationdate(): ?\DateTimeInterface
    {
        return $this->publicationdate;
    }

    public function setPublicationdate(\DateTimeInterface $publicationdate): static
    {
        $this->publicationdate = $publicationdate;

        return $this;
    }

    public function getPublishinghouse(): ?string
    {
        return $this->publishinghouse;
    }

    public function setPublishinghouse(string $publishinghouse): static
    {
        $this->publishinghouse = $publishinghouse;

        return $this;
    }

    public function getAuteur(): ?Auteurs
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteurs $auteur): static
    {
        $this->auteur = $auteur;
        return $this;
    }

}
