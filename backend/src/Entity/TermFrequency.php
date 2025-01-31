<?php

namespace App\Entity;

use App\Repository\TermFrequencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TermFrequencyRepository::class)] // Links the entity to its repository for database operations.
class TermFrequency
{
    // Primary key: Auto-incremented ID for the term frequency record.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Many-to-one relationship with the related question.
    #[ORM\ManyToOne(inversedBy: 'termFrequencies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    // Many-to-one relationship with the associated term.
    #[ORM\ManyToOne(inversedBy: 'termFrequencies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Term $term = null;

    // The frequency count of the term within the question.
    #[ORM\Column]
    private ?int $frequency = null;

    // Constructor initializes the relationships and the frequency count.
    public function __construct(Question $question, Term $term, int $frequency)
    {
        $this->question = $question;
        $this->term = $term;
        $this->frequency = $frequency;
    }

    // Getter and setter for the ID.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter and setter for the associated question.
    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;
        return $this;
    }

    // Getter and setter for the associated term.
    public function getTerm(): ?Term
    {
        return $this->term;
    }

    public function setTerm(?Term $term): static
    {
        $this->term = $term;
        return $this;
    }

    // Getter and setter for the frequency count.
    public function getFrequency(): ?int
    {
        return $this->frequency;
    }

    public function setFrequency(int $frequency): static
    {
        $this->frequency = $frequency;
        return $this;
    }
}
