<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * ImageProcessor — Service terpusat untuk upload & kompresi gambar.
 *
 * Semua gambar akan dikonversi ke WebP dan dikompresi secara otomatis.
 * Menggunakan PHP GD (tidak butuh library eksternal).
 */
class ImageProcessor
{
    /** Kualitas WebP default (0-100). */
    protected int $quality;

    /** Lebar maksimum px. */
    protected int $maxWidth;

    /** Tinggi maksimum px. */
    protected int $maxHeight;

    public function __construct(int $quality = 80, int $maxWidth = 1280, int $maxHeight = 1280)
    {
        $this->quality   = $quality;
        $this->maxWidth  = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * Upload dan kompresi gambar ke disk "public".
     *
     * @param  UploadedFile  $file       File gambar yang diupload
     * @param  string        $directory  Direktori tujuan (relatif ke storage/app/public/)
     * @param  string|null   $filename   Nama file tanpa ekstensi. Jika null, UUID otomatis.
     * @param  int|null      $maxWidth   Override lebar maks untuk upload ini.
     * @param  int|null      $maxHeight  Override tinggi maks untuk upload ini.
     * @param  int|null      $quality    Override kualitas WebP untuk upload ini.
     * @return string|null   Path relatif atau null jika gagal.
     */
    public function store(
        UploadedFile $file,
        string $directory,
        ?string $filename = null,
        ?int $maxWidth = null,
        ?int $maxHeight = null,
        ?int $quality = null,
    ): ?string {
        $maxWidth  = $maxWidth  ?? $this->maxWidth;
        $maxHeight = $maxHeight ?? $this->maxHeight;
        $quality   = $quality   ?? $this->quality;

        $filename = ($filename ?? Str::uuid()->toString()) . '.webp';
        $subPath  = trim($directory, '/') . '/' . $filename;
        $fullPath = storage_path('app/public/' . $subPath);

        // Pastikan direktori ada
        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        // Baca gambar dari file upload
        $imageData = file_get_contents($file->getRealPath());
        $src       = @imagecreatefromstring($imageData);

        if ($src === false) {
            // Fallback: simpan file asli tanpa konversi
            $stored = $file->storeAs($directory, $filename, 'public');
            return $stored ?: null;
        }

        // Konversi palette ke truecolor
        imagepalettetotruecolor($src);
        imagesavealpha($src, true);

        // Hitung ukuran baru
        $srcW = imagesx($src);
        $srcH = imagesy($src);
        [$dstW, $dstH] = $this->calculateDimensions($srcW, $srcH, $maxWidth, $maxHeight);

        // Resize jika perlu
        if ($dstW !== $srcW || $dstH !== $srcH) {
            $dst = imagecreatetruecolor($dstW, $dstH);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefill($dst, 0, 0, $transparent);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);
            imagedestroy($src);
            $src = $dst;
        }

        // Simpan WebP
        $success = imagewebp($src, $fullPath, $quality);
        imagedestroy($src);

        return $success ? $subPath : null;
    }

    /**
     * Hapus file dari disk "public".
     */
    public function delete(string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Hitung dimensi baru dengan mempertahankan aspect ratio.
     *
     * @return array{0: int, 1: int}
     */
    protected function calculateDimensions(int $srcW, int $srcH, int $maxW, int $maxH): array
    {
        if ($srcW <= $maxW && $srcH <= $maxH) {
            return [$srcW, $srcH];
        }

        $ratio = min($maxW / $srcW, $maxH / $srcH);
        return [(int) round($srcW * $ratio), (int) round($srcH * $ratio)];
    }
}
