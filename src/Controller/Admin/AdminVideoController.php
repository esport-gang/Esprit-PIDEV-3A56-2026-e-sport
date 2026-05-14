<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use App\Repository\VideoRepository;
use App\Service\CloudinaryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;




   #[Route('/admin/videos')]
class AdminVideoController extends AbstractController
{
    #[Route('/', name: 'admin_videos', methods: ['GET'])]
    public function index(VideoRepository $videoRepo): Response
    {
        return $this->render('video/admin/index.html.twig', [
            'videos' => $videoRepo->findAll()
        ]);
    }

    #[Route('/upload', name: 'admin_video_upload', methods: ['POST'])]
    public function upload(
        Request $request,
        CloudinaryService $cloudinary,
        EntityManagerInterface $em
    ): Response {

        $file = $request->files->get('video');

        if (!$file) {
            $this->addFlash('error', 'Aucune vidéo sélectionnée');
            return $this->redirectToRoute('admin_videos');
        }

        $data = $cloudinary->uploadVideo($file);

        $video = new Video();
        $video->setTitle($request->get('title'));
        $video->setPath($data['url']);
        $video->setPublicId($data['public_id']);
        $video->setThumbnail($data['thumbnail']);

        $em->persist($video);
        $em->flush();

        $this->addFlash('success', 'Vidéo uploadée avec succès');

        return $this->redirectToRoute('admin_videos');
    }

    #[Route('/delete/{id}', name: 'admin_video_delete', methods: ['GET'])]
    public function delete(
        Video $video,
        CloudinaryService $cloudinary,
        EntityManagerInterface $em
    ): Response {

        if ($video->getPublicId()) {
            $cloudinary->deleteVideo($video->getPublicId());
        }

        $em->remove($video);
        $em->flush();

        $this->addFlash('success', 'Vidéo supprimée');

        return $this->redirectToRoute('admin_videos');
    }
}
