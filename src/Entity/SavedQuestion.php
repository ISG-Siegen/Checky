<?php

namespace App\Entity;

use App\Enum\AnswerType;
use App\Repository\SavedQuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SavedQuestionRepository::class)]
class SavedQuestion
{
    // Primary key: Unique UUID for identifying the saved question.
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups('save:savedChecklist')]
    private ?Uuid $id = null;

    // The text of the saved question, included in serialization for updates and responses.
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['save:savedChecklist', 'save:updateRequest'])]
    private ?string $question = null;

    // Enum type specifying the answer format for the question.
    #[ORM\Column(enumType: AnswerType::class)]
    #[Groups(['save:savedChecklist', 'save:updateRequest'])]
    private ?AnswerType $answerType = null;

    // Reference to the original question this saved question is based on.
    #[ORM\ManyToOne(inversedBy: 'savedQuestions')]
    #[Groups('save:savedChecklist')]
    private ?Question $originalQuestion = null;

    // Reference to the saved checklist this question belongs to.
    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?SavedChecklist $savedChecklist = null;

    // Constructor initializes the mandatory properties.
    public function __construct(string $question, AnswerType $answerType, Question $originalQuestion = null)
    {
        $this->question = $question;
        $this->answerType = $answerType;
        $this->originalQuestion = $originalQuestion;
    }

    // Getter and setter for the ID.
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    // Getter and setter for the question text.
    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): static
    {
        $this->question = $question;
        return $this;
    }

    // Getter and setter for the answer type.
    public function getAnswerType(): ?AnswerType
    {
        return $this->answerType;
    }

    public function setAnswerType(AnswerType $answerType): static
    {
        $this->answerType = $answerType;
        return $this;
    }

    // Getter and setter for the original question.
    public function getOriginalQuestion(): ?Question
    {
        return $this->originalQuestion;
    }

    public function setOriginalQuestion(?Question $originalQuestion): static
    {
        $this->originalQuestion = $originalQuestion;
        return $this;
    }

    // Getter and setter for the saved checklist.
    public function getSavedChecklist(): ?SavedChecklist
    {
        return $this->savedChecklist;
    }

    public function setSavedChecklist(?SavedChecklist $savedChecklist): static
    {
        $this->savedChecklist = $savedChecklist;
        return $this;
    }
}