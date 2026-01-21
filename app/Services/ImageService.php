<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageService
{
    protected string $disk = 'public';
    protected string $path = 'products';

    public function uploadAndConvert(UploadedFile $file, ?string $oldPath = null): string
    {
        try {
            if ($oldPath) {
                $this->deleteImage($oldPath);
            }

            if (!in_array($file->extension(), ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'])) {
                throw new \Exception('Only JPG and PNG images are allowed');
            }

            $filename = Str::random(32) . '.webp';
            $fullPath = $this->path . '/' . $filename;

            $storagePath = storage_path('app/public/' . $fullPath);
            
            if (!file_exists(dirname($storagePath))) {
                mkdir(dirname($storagePath), 0755, true);
            }

            // Try using cwebp (comes with ImageMagick/WebP package)
            // If not available, fall back to just copying the file
            $this->convertToWebp($file->getPathname(), $storagePath);

            return $fullPath;
        } catch (\Exception $e) {
            throw new \Exception('Image upload failed: ' . $e->getMessage());
        }
    }

  
    protected function convertToWebp(string $sourcePath, string $destinationPath): void
    {
        $cwebpPath = $this->findExecutable('cwebp');
        if ($cwebpPath) {
            $command = escapeshellcmd($cwebpPath) . ' ' . escapeshellarg($sourcePath) . ' -o ' . escapeshellarg($destinationPath) . ' -q 80';
            @shell_exec($command);
            
            if (file_exists($destinationPath)) {
                return;
            }
        }

        copy($sourcePath, $destinationPath);
    }


    protected function findExecutable(string $name): ?string
    {
        $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
        $executable = $isWindows ? $name . '.exe' : $name;

        $paths = [
            'C:\\Program Files\\ImageMagick\\' . $executable,
            'C:\\Program Files (x86)\\ImageMagick\\' . $executable,
            '/usr/local/bin/' . $executable,
            '/usr/bin/' . $executable,
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Try which/where command
        $whichCommand = $isWindows ? 'where' : 'which';
        $output = trim(@shell_exec($whichCommand . ' ' . $name . ' 2>nul'));
        
        return !empty($output) ? $output : null;
    }

    public function deleteImage(string $path): bool
    {
        try {
            $fullPath = storage_path('app/public/' . $path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

  
    public function getUrl(string $path): string
    {
        return asset('storage/' . $path);
    }


    public function downloadAndConvert(string $imageUrl): ?string
    {
        try {
            // Validate URL
            if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                throw new \Exception('Invalid image URL');
            }

            // Download image
            $imageContent = file_get_contents($imageUrl);
            if (!$imageContent) {
                throw new \Exception('Failed to download image from URL');
            }

            // Determine file extension from URL or content
            $urlPath = parse_url($imageUrl, PHP_URL_PATH);
            $extension = pathinfo($urlPath, PATHINFO_EXTENSION);

            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                throw new \Exception('Image must be JPG or PNG format');
            }

            // Generate unique filename
            $filename = Str::random(32) . '.webp';
            $fullPath = $this->path . '/' . $filename;
            $storagePath = storage_path('app/public/' . $fullPath);

            // Ensure directory exists
            if (!file_exists(dirname($storagePath))) {
                mkdir(dirname($storagePath), 0755, true);
            }

            // Save the downloaded content
            file_put_contents($storagePath, $imageContent);

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('Image download failed: ' . $e->getMessage());
            return null;
        }
    }
}
