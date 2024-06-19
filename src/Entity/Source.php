<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\SourceRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SourceRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'conference:list'])
    ]
)]
class Source
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;
    
    #[ORM\Column(length: 255)]
    #[Groups(['conference:list'])]
    private ?string $name = null;
    
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;
    
    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    #[Groups(['conference:list'])]
    private ?int $year = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var Collection<int, Checklist>
     */
    #[ORM\ManyToMany(targetEntity: Checklist::class, mappedBy: 'published_in')]
    private Collection $checklists;

    public function __construct($name = null)
    {
        $this->checklists = new ArrayCollection();

        $this->created_at = new DateTimeImmutable();
        $this->name = $name;
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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;

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
     * @return Collection<int, Checklist>
     */
    public function getChecklists(): Collection
    {
        return $this->checklists;
    }

    public function addChecklist(Checklist $checklist): static
    {
        if (!$this->checklists->contains($checklist)) {
            $this->checklists->add($checklist);
            $checklist->addPublishedIn($this);
        }

        return $this;
    }

    public function removeChecklist(Checklist $checklist): static
    {
        if ($this->checklists->removeElement($checklist)) {
            $checklist->removePublishedIn($this);
        }

        return $this;
    }
}
