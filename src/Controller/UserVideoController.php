<?php

namespace App\Controller;

use App\Entity\Video;
use App\Entity\VideoComment;
use App\Entity\VideoReaction;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/videos')]
class UserVideoController extends AbstractController
{
    #[Route('/', name: 'video_list', methods: ['GET'])]
    public function index(VideoRepository $repo): Response
    {
        $videos = $repo->findAll();

        return $this->render('video/user_video/index.html.twig', [
            'videos' => $videos,
            'video' => $videos[0] ?? null
        ]);
    }

    #[Route('/{id}', name: 'video_show', methods: ['GET'])]
    public function show(Video $video, VideoRepository $repo): Response
    {
        return $this->render('video/user_video/index.html.twig', [
            'videos' => $repo->findAll(),
            'video' => $video
        ]);
    }

    #[Route('/{id}/comment', name: 'video_comment', methods: ['POST'])]
    public function comment(Video $video, Request $request, EntityManagerInterface $em): Response
    {
        $body = trim($request->request->get('body'));

        if ($body !== '') {
            $comment = new VideoComment();
            $comment->setVideo($video);

            $username = $this->getUser()
                ? $this->getUser()->getUserIdentifier()
                : 'Guest';

            $comment->setUsername($username);
            $comment->setBody($body);

            $em->persist($comment);
            $em->flush();
        }

        return $this->redirectToRoute('video_show', ['id' => $video->getId()]);
    }

    #[Route('/{id}/react/{type}', name: 'video_react', methods: ['GET'])]
    public function react(Video $video, string $type, EntityManagerInterface $em): Response
    {
        $reaction = new VideoReaction();
        $reaction->setVideo($video);

        $username = $this->getUser()
            ? $this->getUser()->getUserIdentifier()
            : 'Guest';

        $reaction->setUsername($username);
        $reaction->setType($type);
        $reaction->setCreatedAt(new \DateTimeImmutable());

        $em->persist($reaction);
        $em->flush();

        return $this->redirectToRoute('video_show', ['id' => $video->getId()]);
    }
}