<?php

namespace App\Entity;

use App\Repository\ConferenceInstanceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ConferenceInstanceRepository::class)]
class ConferenceInstance
{
    // Primary key: Unique UUID for identifying the conference instance.
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['archive:years', 'archive:details'])]
    private ?Uuid $id = null;

    // Year of the conference instance.
    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['archive:years', 'archive:details', 'question:get_questions'])]
    private ?int $year = null;

    // Many-to-one relationship with the parent conference.
    #[ORM\ManyToOne(inversedBy: 'instances')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['question:get_questions'])]
    private ?Conference $conference = null;
    
    /**
     * @var Collection<int, Url>
     */
    // Many-to-many relationship with associated URLs.
    #[ORM\ManyToMany(targetEntity: Url::class, inversedBy: 'conferenceInstances')]
    #[Groups(['archive:details'])]
    private Collection $url;

    // Creation timestamp for tracking when this instance was created.
    #[ORM\Column]
    #[Groups(['archive:details'])]
    private ?\DateTimeImmutable $created_at = null;

    // Prompts specific to the author and reviewer of the conference instance.
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['archive:details'])]
    private ?string $author_prompt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['archive:details'])]
    private ?string $reviewer_prompt = null;

    /**
     * @var Collection<int, QuestionGroup>
     */
    // Many-to-many relationships with question groups and questions.
    #[ORM\ManyToMany(targetEntity: QuestionGroup::class, mappedBy: 'conference')]
    private Collection $questionGroups;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\ManyToMany(targetEntity: Question::class, mappedBy: 'conference')]
    private Collection $questions;

    // Constructor initializes default values and required properties.
    public function __construct(int $year, Conference $conference)
    {
        $this->year = $year;
        $this->conference = $conference;
        $this->url = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
        $this->questionGroups = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    // Getters and setters for the ID and year.
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;
        return $this;
    }

    // Accessors for the related conference entity.
    public function getConference(): ?Conference
    {
        return $this->conference;
    }

    public function setConference(?Conference $conference): static
    {
        $this->conference = $conference;
        return $this;
    }

    /**
     * @return Collection<int, Url>
     */
    // Accessors for URLs associated with this instance.
    public function getUrl(): Collection
    {
        return $this->url;
    }

    public function addUrl(Url $url): static
    {
        if (!$this->url->contains($url)) {
            $this->url->add($url);
        }
        return $this;
    }

    public function removeUrl(Url $url): static
    {
        $this->url->removeElement($url);
        return $this;
    }

    // Accessors for creation timestamp.
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    // Getters and setters for author and reviewer prompts.
    public function getAuthorPrompt(): ?string
    {
        return $this->author_prompt;
    }

    public function setAuthorPrompt(?string $author_prompt): static
    {
        $this->author_prompt = $author_prompt;
        return $this;
    }

    public function getReviewerPrompt(): ?string
    {
        return $this->reviewer_prompt;
    }

    public function setReviewerPrompt(?string $reviewer_prompt): static
    {
        $this->reviewer_prompt = $reviewer_prompt;
        return $this;
    }

    /**
     * @return Collection<int, QuestionGroup>
     */
    // Accessors for related question groups.
    public function getQuestionGroups(): Collection
    {
        return $this->questionGroups;
    }

    public function addQuestionGroup(QuestionGroup $questionGroup): static
    {
        if (!$this->questionGroups->contains($questionGroup)) {
            $this->questionGroups->add($questionGroup);
            $questionGroup->addConference($this);
        }
        return $this;
    }

    public function removeQuestionGroup(QuestionGroup $questionGroup): static
    {
        if ($this->questionGroups->removeElement($questionGroup)) {
            $questionGroup->removeConference($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    // Accessors for related questions.
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->addConference($this);
        }
        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            $question->removeConference($this);
        }
        return $this;
    }
}