<?php

namespace App\Entity;

use App\Repository\TermFrequencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TermFrequencyRepository::class)]
class TermFrequency
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'termFrequencies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne(inversedBy: 'termFrequencies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Term $term = null;

    #[ORM\Column]
    private ?int $frequency = null;


    public function __construct(Question $question, Term $term, int $frequency) {
        $this->question = $question;
        $this->term = $term;
        $this->frequency = $frequency;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getTerm(): ?Term
    {
        return $this->term;
    }

    public function setTerm(?Term $term): static
    {
        $this->term = $term;

        return $this;
    }

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
