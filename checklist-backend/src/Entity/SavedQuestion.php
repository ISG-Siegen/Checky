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
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups('save:savedChecklist')]
    private ?Uuid $id = null;
    
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['save:savedChecklist', 'save:updateRequest'])]
    private ?string $question = null;
    
    #[ORM\Column(enumType: AnswerType::class)]
    #[Groups(['save:savedChecklist', 'save:updateRequest'])]
    private ?AnswerType $answerType = null;
    
    #[ORM\ManyToOne(inversedBy: 'savedQuestions')]
    #[Groups('save:savedChecklist')]
    private ?Question $originalQuestion = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    private ?SavedChecklist $savedChecklist = null;


    public function __construct(string $question, AnswerType $answerType, Question $originalQuestion = null) {
        $this->question = $question;
        $this->answerType = $answerType;
        $this->originalQuestion = $originalQuestion;
    }
    

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getOriginalQuestion(): ?Question
    {
        return $this->originalQuestion;
    }

    public function setOriginalQuestion(?Question $originalQuestion): static
    {
        $this->originalQuestion = $originalQuestion;

        return $this;
    }

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
