<?php

namespace App\Controller\Admin;

use App\Service\StreamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

#[Route('/admin/stream')]
class AdminStreamController extends AbstractController
{
    #[Route('', name: 'admin_stream', methods: ['GET'])]
    public function index(StreamService $streamService): Response
    {
        $streamService->ensureStreamExists();

        return $this->render('stream/admin/index.html.twig', [
            'stream' => $streamService->getActiveStream(),
        ]);
    }

    // ================= CHAT =================
    #[Route('/chat/{id}', name: 'admin_stream_chat', methods: ['POST'])]
    public function chat(int $id, Request $request, HubInterface $hub): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return new Response('Invalid JSON', 400);
        }

        $message = trim($data['message'] ?? '');

        if ($message === '') {
            return new Response('Empty message', 400);
        }

        $hub->publish(new Update(
            'stream/' . $id,
            json_encode([
                'type' => 'chat',
                'user' => 'Admin',
                'message' => $message,
                'time' => time()
            ])
        ));

        return new Response('ok');
    }

    // ================= REACTIONS =================
    #[Route('/react/{id}/{type}', name: 'admin_stream_react', methods: ['POST'])]
    public function react(int $id, string $type, HubInterface $hub): Response
    {
        $emojis = [
            'like' => '👍',
            'love' => '❤️',
            'haha' => '😂',
            'wow'  => '😮'
        ];

        if (!isset($emojis[$type])) {
            return new Response('Invalid reaction', 400);
        }

        $hub->publish(new Update(
            'stream/' . $id,
            json_encode([
                'type' => 'reaction',
                'user' => 'Admin',
                'reaction' => $type,
                'emoji' => $emojis[$type],
                'time' => time()
            ])
        ));

        return new Response('ok');
    }
}