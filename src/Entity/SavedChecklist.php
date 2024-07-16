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
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups('save:savedChecklist')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['save:savedChecklist', 'save:updateRequest'])]
    private ?string $name = null;
    
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;
    
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;
    
    /**
     * @var Collection<int, SavedQuestion>
     */
    #[ORM\OneToMany(targetEntity: SavedQuestion::class, mappedBy: 'savedChecklist')]
    #[Groups(['save:savedChecklist', 'save:updateRequest'])]
    private Collection $questions;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->created_at = new DateTimeImmutable();
        $this->updated_at = new DateTime();
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?Uuid
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
