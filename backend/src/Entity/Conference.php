<?php

namespace App\Entity;

use App\Repository\ConferenceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
class Conference
{
    // Primary key: UUID for uniquely identifying a conference.
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['archive:get_names'])]
    private ?Uuid $id = null;

    // Name of the conference, exposed in certain serialization contexts.
    #[ORM\Column(length: 255)]
    #[Groups(['archive:get_names', 'question:get_questions'])]
    private ?string $name = null;

    // Optional description for additional details about the conference.
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, ConferenceInstances>
     */
    #[ORM\OneToMany(targetEntity: ConferenceInstance::class, mappedBy: 'conference', orphanRemoval: true)]
    private Collection $instances;

    // Tracks when the conference was created.
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    // Constructor initializes default values like name, description, and creation date.
    public function __construct(string $name, string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->instances = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
    }

    // Getter for the conference ID.
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    // Getter and setter for the conference name.
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    // Getter and setter for the description.
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection<int, ConferenceInstances>
     */
    // Accessor for the related conference instances.
    public function getInstances(): Collection
    {
        return $this->instances;
    }

    // Adds a new instance to the conference and sets the relationship.
    public function addInstance(ConferenceInstance $instance): static
    {
        if (!$this->instances->contains($instance)) {
            $this->instances->add($instance);
            $instance->setConference($this);
        }
        return $this;
    }

    // Removes an instance and ensures the relationship is cleared.
    public function removeInstance(ConferenceInstance $instance): static
    {
        if ($this->instances->removeElement($instance)) {
            // set the owning side to null (unless already changed)
            if ($instance->getConference() === $this) {
                $instance->setConference(null);
            }
        }
        return $this;
    }

    // Getter for the creation date.
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    // Setter for the creation date (if it needs to be updated).
    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }
}