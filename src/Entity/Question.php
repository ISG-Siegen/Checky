<?php

namespace App\Entity;

use App\Enum\AnswerType;
use App\Repository\QuestionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['question:get_questions', 'save:updateRequest', 'save:savedChecklist'])]
    private ?Uuid $id = null;   
    
    #[ORM\ManyToMany(targetEntity: QuestionGroup::class, mappedBy: 'questions')]
    #[Groups(['question:get_questions'])]
    private Collection $questionGroups;
    
    /**
     * @var Collection<int, ConferenceInstance>
     */
    #[ORM\ManyToMany(targetEntity: ConferenceInstance::class, inversedBy: 'questions')]
    #[Groups(['question:get_questions'])]
    private Collection $conference;
    
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['question:get_questions'])]
    private ?string $question = null;
    
    #[ORM\Column(enumType: AnswerType::class)]
    #[Groups(['question:get_questions'])]
    private ?AnswerType $answerType = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, SavedQuestion>
     */
    #[ORM\OneToMany(targetEntity: SavedQuestion::class, mappedBy: 'originalQuestion')]
    private Collection $savedQuestions;

    /**
     * @var Collection<int, TermFrequency>
     */
    #[ORM\OneToMany(targetEntity: TermFrequency::class, mappedBy: 'question')]
    private Collection $termFrequencies;

    public function __construct(string $question, AnswerType $answerType)
    {
        $this->conference = new ArrayCollection();
        $this->questionGroups = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();

        $this->question = $question;
        $this->answerType = $answerType;
        $this->savedQuestions = new ArrayCollection();
        $this->termFrequencies = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    /**
     * @return Collection<int, QuestionGroup>
     */
    public function getQuestionGroups(): Collection
    {
        return $this->questionGroups;
    }

    public function addQuestionGroup(QuestionGroup $questionGroup): static
    {
        if (!$this->questionGroups->contains($questionGroup)) {
            $this->questionGroups->add($questionGroup);
            $questionGroup->addQuestion($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ConferenceInstance>
     */
    public function getConference(): Collection
    {
        return $this->conference;
    }

    public function addConference(ConferenceInstance $conference): static
    {
        if (!$this->conference->contains($conference)) {
            $this->conference->add($conference);
        }

        return $this;
    }

    public function removeConference(ConferenceInstance $conference): static
    {
        $this->conference->removeElement($conference);

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswerType(): ?AnswerType
    {
        return $this->answerType;
    }

    public function setAnswerType(AnswerType $answerType): static
    {
        $this->answerType = $answerType;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, SavedQuestion>
     */
    public function getSavedQuestions(): Collection
    {
        return $this->savedQuestions;
    }

    public function addSavedQuestion(SavedQuestion $savedQuestion): static
    {
        if (!$this->savedQuestions->contains($savedQuestion)) {
            $this->savedQuestions->add($savedQuestion);
            $savedQuestion->setOriginalQuestion($this);
        }

        return $this;
    }

    public function removeSavedQuestion(SavedQuestion $savedQuestion): static
    {
        if ($this->savedQuestions->removeElement($savedQuestion)) {
            // set the owning side to null (unless already changed)
            if ($savedQuestion->getOriginalQuestion() === $this) {
                $savedQuestion->setOriginalQuestion(null);
            }
        }

        return $this;
    }

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
            $termFrequency->setQuestion($this);
        }

        return $this;
    }

    public function removeTermFrequency(TermFrequency $termFrequency): static
    {
        if ($this->termFrequencies->removeElement($termFrequency)) {
            // set the owning side to null (unless already changed)
            if ($termFrequency->getQuestion() === $this) {
                $termFrequency->setQuestion(null);
            }
        }

        return $this;
    }
}
