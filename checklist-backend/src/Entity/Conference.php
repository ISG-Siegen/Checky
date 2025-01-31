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
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['archive:get_names'])]
    private ?Uuid $id = null;
    
    #[ORM\Column(length: 255)]
    #[Groups(['archive:get_names', 'question:get_questions'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, ConferenceInstances>
     */
    #[ORM\OneToMany(targetEntity: ConferenceInstance::class, mappedBy: 'conference', orphanRemoval: true)]
    private Collection $instances;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct(string $name, string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->instances = new ArrayCollection();
        $this->created_at = new DateTimeImmutable();
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
    public function getInstances(): Collection
    {
        return $this->instances;
    }

    public function addInstance(ConferenceInstance $instance): static
    {
        if (!$this->instances->contains($instance)) {
            $this->instances->add($instance);
            $instance->setConference($this);
        }

        return $this;
    }

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
