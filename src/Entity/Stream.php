<?php

// src/Entity/Stream.php

namespace App\Entity;

use App\Repository\StreamRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StreamRepository::class)]
class Stream
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private bool $active = false;

    public function getId(): ?int { return $this->id; }

    public function getUrl(): ?string { return $this->url; }

    public function setUrl(string $url): self {
        $this->url = $url;
        return $this;
    }

    public function isActive(): bool {
        return $this->active;
    }

    public function setActive(bool $active): self {
        $this->active = $active;
        return $this;
    }
}