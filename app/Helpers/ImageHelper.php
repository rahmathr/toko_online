<?php

namespace App\Helpers;

use GdImage;
use Illuminate\Http\UploadedFile;

class ImageHelper
{
    /**
     * Upload dan resize gambar
     *
     * @param UploadedFile $file File gambar yang diupload
     * @param string $directory Direktori tujuan (relatif terhadap public/)
     * @param string $fileName Nama file hasil
     * @param int|null $width Lebar gambar hasil (opsional)
     * @param int|null $height Tinggi gambar hasil (opsional)
     * @return string Nama file yang berhasil diupload
     * @throws \Exception Jika tipe gambar tidak didukung
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
     *
     * @param string $path Path file gambar
     * @param string $extension Ekstensi file
     * @return GdImage Object gambar GD
     * @throws \Exception Jika tipe gambar tidak didukung
     */
    private static function createImageFromPath(string $path, string $extension): GdImage
    {
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $img = imagecreatefromjpeg($path);
                break;
            case 'png':
                $img = imagecreatefrompng($path);
                break;
            case 'gif':
                $img = imagecreatefromgif($path);
                break;
            default:
                throw new \Exception('Unsupported image type: ' . $extension);
        }

        if (!$img instanceof GdImage) {
            throw new \Exception('Failed to create image from path');
        }

        return $img;
    }

    /**
     * Resize gambar dengan mempertahankan aspect ratio
     *
     * @param GdImage $image Object gambar GD
     * @param int $width Lebar target
     * @param int|null $height Tinggi target (opsional)
     * @return GdImage Object gambar hasil resize
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
            $oldWidth, $oldHeight
        );

        imagedestroy($image);

        return $newImage;
    }

    /**
     * Simpan gambar ke file
     *
     * @param GdImage $image Object gambar GD
     * @param string $destinationPath Path file tujuan
     * @param string $extension Ekstensi file
     * @return void
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
