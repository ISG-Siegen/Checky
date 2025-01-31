<?php

namespace App\Entity;

use App\Repository\SavedChecklistRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SavedChecklistRepository::class)]
class SavedChecklist
{
    // Primary key: Unique UUID for the saved checklist.
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups('save:savedChecklist')]
    private ?Uuid $id = null;

    // Name of the checklist, included in serialization groups for updates and responses.
    #[ORM\Column(length: 255)]
    #[Groups(['save:savedChecklist', 'save:updateRequest'])]
    private ?string $name = null;

    // Timestamp for when the checklist was created.
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    // Timestamp for the last update to the checklist.
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;
    /**
     * @var Collection<int, SavedQuestion>
     */

    // One-to-many relationship with SavedQuestion.
    #[ORM\OneToMany(targetEntity: SavedQuestion::class, mappedBy: 'savedChecklist')]
    #[Groups(['save:savedChecklist', 'save:updateRequest'])]
    private Collection $questions;

    // Constructor initializes default values for the checklist.
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTime();
        $this->questions = new ArrayCollection();
    }

    // Getters and setters for the ID.
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    // Getters and setters for the name.
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    // Accessors for creation and update timestamps.
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    // Accessors for related questions.
    /**
     * @return Collection<int, SavedQuestion>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(SavedQuestion $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setSavedChecklist($this);
        }
        return $this;
    }

    public function removeQuestion(SavedQuestion $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getSavedChecklist() === $this) {
                $question->setSavedChecklist(null);
            }
        }
        return $this;
    }

    // Sets a new collection of questions, replacing the old ones.
    /**
     * @param SavedQuestion[] $questions
     */
    public function setQuestions(array $questions)
    {
        foreach ($this->questions as $oldQuestion) {
            $this->removeQuestion($oldQuestion);
        }

        foreach ($questions as $newQuestion) {
            $this->addQuestion($newQuestion);
        }
    }
}