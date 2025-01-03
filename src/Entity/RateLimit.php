<?php

namespace App\Entity;

use App\Repository\RateLimitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RateLimitRepository::class)]
class RateLimit
{
    // Primary key: Auto-incremented ID for the rate limit record.
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Timestamp of when the rate limit was requested.
    #[ORM\Column]
    private ?\DateTimeImmutable $requested_at = null;

    // Constructor initializes the requested_at timestamp to the current time.
    public function __construct()
    {
        $this->requested_at = new \DateTimeImmutable();
    }

    // Getter for the ID.
    public function getId(): ?int
    {
        return $this->id;
    }

    // Getter and setter for the requested_at timestamp.
    public function getRequestedAt(): ?\DateTimeImmutable
    {
        return $this->requested_at;
    }

    public function setRequestedAt(\DateTimeImmutable $requested_at): static
    {
        $this->requested_at = $requested_at;

        return $this;
    }
}