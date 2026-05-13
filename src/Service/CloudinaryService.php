<?php

namespace App\Service;

use Cloudinary\Cloudinary;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CloudinaryService
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $_ENV['CLOUDINARY_CLOUD_NAME'],
                'api_key'    => $_ENV['CLOUDINARY_API_KEY'],
                'api_secret' => $_ENV['CLOUDINARY_API_SECRET'],
            ],
            'url' => [
                'secure' => true,
            ],
        ]);
    }

    public function uploadVideo(UploadedFile $file): array
    {
        $result = $this->cloudinary
            ->uploadApi()
            ->upload(
                $file->getRealPath(),
                [
                    'resource_type' => 'video',
                    'folder' => 'videos'
                ]
            );

        return [
            'url' => $result['secure_url'],
            'public_id' => $result['public_id'],
            'thumbnail' =>
                'https://res.cloudinary.com/' .
                $_ENV['CLOUDINARY_CLOUD_NAME'] .
                '/video/upload/so_2,w_400,h_250,c_fill/' .
                $result['public_id'] .
                '.jpg'
        ];
    }

    public function deleteVideo(string $publicId): void
    {
        $this->cloudinary
            ->uploadApi()
            ->destroy($publicId, [
                'resource_type' => 'video'
            ]);
    }
}