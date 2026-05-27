<?php

namespace App\Helpers;

use GdImage;
use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * Upload dan resize gambar
     */
    public static function uploadAndResize(
        UploadedFile $file,
        string $directory,
        string $fileName,
        ?int $width = null,
        ?int $height = null
    ): string {
        $destinationPath = public_path($directory);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension());
        $image = self::createImageFromPath($file->getRealPath(), $extension);

        if ($width) {
            $image = self::resizeImage($image, $width, $height);
        }

        self::saveImage($image, $destinationPath . '/' . $fileName, $extension);
        imagedestroy($image);

        return $fileName;
    }

    /**
     * Buat resource gambar dari file path
     */
    private static function createImageFromPath(string $path, string $extension): GdImage
    {
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $img = @imagecreatefromjpeg($path);
                break;
            case 'png':
                $img = @imagecreatefrompng($path);
                break;
            case 'gif':
                $img = @imagecreatefromgif($path);
                break;
            default:
                throw new \Exception('Unsupported image type: ' . $extension);
        }

        if ($img === false) {
            throw new \Exception('Failed to create image from path: ' . $path);
        }

        return $img;
    }

    /**
     * Resize gambar dengan mempertahankan aspect ratio
     */
    private static function resizeImage(GdImage $image, int $width, ?int $height = null): GdImage
    {
        $oldWidth = imagesx($image);
        $oldHeight = imagesy($image);
        $aspectRatio = $oldWidth / $oldHeight;

        if (!$height) {
            $height = (int) round($width / $aspectRatio);
        }

        $newImage = imagecreatetruecolor($width, $height);

        // Handle transparansi untuk PNG
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);

        imagecopyresampled(
            $newImage,
            $image,
            0, 0, 0, 0,
            $width, $height,
            $oldWidth,
            $oldHeight
        );

        imagedestroy($image);

        return $newImage;
    }

    /**
     * Simpan gambar ke file
     */
    private static function saveImage(GdImage $image, string $destinationPath, string $extension): void
    {
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($image, $destinationPath, 90);
                break;
            case 'png':
                imagepng($image, $destinationPath, 9);
                break;
            case 'gif':
                imagegif($image, $destinationPath);
                break;
        }
    }
}
