<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UrlRepository::class)]
class Url
{
    // Primary key: Unique UUID to identify the URL.
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['archive:details'])]
    private ?Uuid $id = null;

    // Name or description of the URL.
    #[ORM\Column(length: 255)]
    #[Groups(['archive:details'])]
    private ?string $name = null;

    // The actual URL string.
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['archive:details'])]
    private ?string $url = null;

    // Timestamp for when the URL record was created.
    #[ORM\Column]
    #[Groups(['archive:details'])]
    private ?\DateTimeImmutable $created_at = null;

    // Many-to-many relationship with conference instances.
    /**
     * @var Collection<int, ConferenceInstances>
     */
    #[ORM\ManyToMany(targetEntity: ConferenceInstance::class, mappedBy: 'url')]
    private Collection $conferenceInstances;

    // Constructor initializes default values and required properties.
    public function __construct($name = null, $url = null)
    {
        $this->created_at = new DateTimeImmutable();
        $this->name = $name;
        $this->url = $url;
        $this->conferenceInstances = new ArrayCollection();
    }

    // Getter and setter for the ID.
    public function getId(): ?Uuid
    {
        return $this->id;
    }

    // Getter and setter for the name or description.
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    // Getter and setter for the URL string.
    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;
        return $this;
    }

    // Getter and setter for the creation timestamp.
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;
        return $this;
    }

    // Accessor methods for related conference instances.
    /**
     * @return Collection<int, ConferenceInstances>
     */
    public function getConferenceInstances(): Collection
    {
        return $this->conferenceInstances;
    }

    public function addConferenceInstance(ConferenceInstance $conferenceInstance): static
    {
        if (!$this->conferenceInstances->contains($conferenceInstance)) {
            $this->conferenceInstances->add($conferenceInstance);
            $conferenceInstance->addUrl($this);
        }
        return $this;
    }

    public function removeConferenceInstance(ConferenceInstance $conferenceInstance): static
    {
        if ($this->conferenceInstances->removeElement($conferenceInstance)) {
            $conferenceInstance->removeUrl($this);
        }
        return $this;
    }
}