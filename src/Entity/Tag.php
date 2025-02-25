<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Bookmark>
     */
    #[ORM\ManyToMany(targetEntity: Bookmark::class, inversedBy: 'Tags')]
    private Collection $Bookmark;

    public function __construct()
    {
        $this->Bookmark = new ArrayCollection();
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

    /**
     * @return Collection<int, Bookmark>
     */
    public function getBookmark(): Collection
    {
        return $this->Bookmark;
    }

    public function addBookmark(Bookmark $bookmark): static
    {
        if (!$this->Bookmark->contains($bookmark)) {
            $this->Bookmark->add($bookmark);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): static
    {
        $this->Bookmark->removeElement($bookmark);

        return $this;
    }
}
