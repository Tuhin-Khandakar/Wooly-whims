<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    private $manager;

    public function __construct()
    {
        try {
            $this->manager = new ImageManager(new Driver());
        } catch (\Exception $e) {
            $this->manager = null;
        }
    }

    public function upload(UploadedFile $file, string $directory = 'products'): string
    {
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $filename;

        try {
            if (!$this->manager) {
                throw new \Exception('Image manager not initialized.');
            }
            // Resize using Intervention Image
            $image = $this->manager->read($file);
            $image->scale(width: 800);
            Storage::disk('public')->put($path, (string) $image->encode());
        } catch (\Exception $e) {
            // Fallback to basic upload if GD/Intervention fails
            Storage::disk('public')->putFileAs($directory, $file, $filename);
        }

        return $path;
    }

    public function delete(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
