<?php

namespace App\Controller;

use App\Repository\StreamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class UserStreamController extends AbstractController
{
    #[Route('/stream/live', name: 'app_user_stream', methods: ['GET'])]
    public function index(StreamRepository $streamRepository): Response
    {
        $stream = $streamRepository->findOneBy(['active' => true]);

        return $this->render('stream/user_stream/index.html.twig', [
            'stream' => $stream
        ]);
    }

    // ================= USER CHAT =================
    #[Route('/stream/chat/{id}', name: 'user_stream_chat', methods: ['POST'])]
    public function chat(int $id, Request $request, HubInterface $hub): Response
    {
        $data = json_decode($request->getContent(), true);

        $message = trim($data['message'] ?? '');
        $user = trim($data['user'] ?? 'User');

        if ($message === '') {
            return new Response('Empty message', 400);
        }

        $hub->publish(new Update(
            'stream/' . $id,
            json_encode([
                'type' => 'chat',
                'user' => $user,
                'message' => $message,
                'time' => time()
            ])
        ));

        return new Response('ok');
    }

    // ================= USER REACTION =================
    #[Route('/stream/react/{id}/{type}', name: 'user_stream_react', methods: ['POST'])]
    public function react(int $id, string $type, HubInterface $hub): Response
    {
        $emojis = [
            'like' => '👍',
            'love' => '❤️',
            'haha' => '😂',
            'wow'  => '😮'
        ];

        $emoji = $emojis[$type] ?? '👍';

        $hub->publish(new Update(
            'stream/' . $id,
            json_encode([
                'type' => 'reaction',
                'reaction' => $type,
                'emoji' => $emoji,
                'time' => time()
            ])
        ));

        return new Response('ok');
    }
}