<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    private ?string $path = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $publicId = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $thumbnail = null;

    #[ORM\Column]
    private int $reactionsCount = 0;

    #[ORM\Column]
    private int $commentsCount = 0;

    #[ORM\OneToMany(
        mappedBy: 'video',
        targetEntity: VideoComment::class,
        orphanRemoval: true
    )]
    private Collection $videoComments;

    #[ORM\OneToMany(
        mappedBy: 'video',
        targetEntity: VideoReaction::class,
        orphanRemoval: true
    )]
    private Collection $videoReactions;

    public function __construct()
    {
        $this->videoComments = new ArrayCollection();
        $this->videoReactions = new ArrayCollection();
    }

    // ================= ID =================

    public function getId(): ?int
    {
        return $this->id;
    }

    // ================= TITLE =================

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    // ================= PATH =================

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    // ================= PUBLIC ID =================

    public function getPublicId(): ?string
    {
        return $this->publicId;
    }

    public function setPublicId(?string $publicId): static
    {
        $this->publicId = $publicId;

        return $this;
    }

    // ================= THUMBNAIL =================

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    // ================= COUNTS =================

    public function getReactionsCount(): int
    {
        return $this->reactionsCount;
    }

    public function setReactionsCount(int $reactionsCount): static
    {
        $this->reactionsCount = $reactionsCount;

        return $this;
    }

    public function getCommentsCount(): int
    {
        return $this->commentsCount;
    }

    public function setCommentsCount(int $commentsCount): static
    {
        $this->commentsCount = $commentsCount;

        return $this;
    }

    // ================= COMMENTS =================

    /**
     * @return Collection<int, VideoComment>
     */
    public function getVideoComments(): Collection
    {
        return $this->videoComments;
    }

    public function addVideoComment(VideoComment $videoComment): static
    {
        if (!$this->videoComments->contains($videoComment)) {

            $this->videoComments->add($videoComment);

            $videoComment->setVideo($this);
        }

        return $this;
    }

    public function removeVideoComment(VideoComment $videoComment): static
    {
        $this->videoComments->removeElement($videoComment);

        return $this;
    }

    // ================= REACTIONS =================

    /**
     * @return Collection<int, VideoReaction>
     */
    public function getVideoReactions(): Collection
    {
        return $this->videoReactions;
    }

    public function addVideoReaction(VideoReaction $videoReaction): static
    {
        if (!$this->videoReactions->contains($videoReaction)) {

            $this->videoReactions->add($videoReaction);

            $videoReaction->setVideo($this);
        }

        return $this;
    }

    public function removeVideoReaction(VideoReaction $videoReaction): static
    {
        $this->videoReactions->removeElement($videoReaction);

        return $this;
    }
}