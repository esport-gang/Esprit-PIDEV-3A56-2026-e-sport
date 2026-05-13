<?php

namespace App\Service;

use App\Repository\StreamRepository;
use App\Entity\Stream;
use Doctrine\ORM\EntityManagerInterface;

class StreamService
{
    public function __construct(
        private StreamRepository $repo,
        private EntityManagerInterface $em
    ) {}

    public function getActiveStream(): ?Stream
    {
        return $this->repo->findOneBy(['active' => true]);
    }

    public function ensureStreamExists(): void
    {
        $stream = $this->repo->findOneBy([]);

        if (!$stream) {
            $stream = new Stream();
            $stream->setUrl('http://100.89.37.94:8080/hls/match1.m3u8');
            $stream->setActive(true);

            $this->em->persist($stream);
            $this->em->flush();
        }
    }
}