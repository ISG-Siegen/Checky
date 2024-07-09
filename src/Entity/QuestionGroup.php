<?php

namespace App\Entity;

use App\Repository\QuestionGroupRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: QuestionGroupRepository::class)]
class QuestionGroup
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['question:get_questions'])]
    private ?Uuid $id = null;
    
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['question:get_questions'])]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, ConferenceInstance>
     */
    #[ORM\ManyToMany(targetEntity: ConferenceInstance::class, inversedBy: 'questionGroups')]
    private Collection $conference;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\ManyToMany(targetEntity: Question::class, inversedBy: 'questionGroups')]
    private Collection $questions;

    public function __construct(string $description)
    {
        $this->conference = new ArrayCollection();
        $this->questions = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();

        $this->description = $description;
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        $this->questions->removeElement($question);

        return $this;
    }
}
