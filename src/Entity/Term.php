<?php

namespace App\Entity;

use App\Repository\TermRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TermRepository::class)] // Links the entity to its repository for database operations.
class Term
{
    // Primary key: Auto-incremented ID for the term.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // The unique term string stored in the entity.
    #[ORM\Column(length: 255, unique: true)]
    private ?string $term = null;

    /**
     * @var Collection<int, TermFrequency>
     */
    // One-to-many relationship with TermFrequency.
    #[ORM\OneToMany(targetEntity: TermFrequency::class, mappedBy: 'term')]
    private Collection $termFrequencies;

    // Constructor initializes the term and the termFrequencies collection.
    public function __construct(string $term)
    {
        $this->termFrequencies = new ArrayCollection();
        $this->term = $term;
    }

    // Getter and setter for the ID.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter and setter for the term string.
    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function setTerm(string $term): static
    {
        $this->term = $term;
        return $this;
    }

    // Accessors for term frequencies.
    /**
     * @return Collection<int, TermFrequency>
     */
    public function getTermFrequencies(): Collection
    {
        return $this->termFrequencies;
    }

    public function addTermFrequency(TermFrequency $termFrequency): static
    {
        if (!$this->termFrequencies->contains($termFrequency)) {
            $this->termFrequencies->add($termFrequency);
            $termFrequency->setTerm($this);
        }
        return $this;
    }

    public function removeTermFrequency(TermFrequency $termFrequency): static
    {
        if ($this->termFrequencies->removeElement($termFrequency)) {
            // set the owning side to null (unless already changed)
            if ($termFrequency->getTerm() === $this) {
                $termFrequency->setTerm(null);
            }
        }
        return $this;
    }
}
