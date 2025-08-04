<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
        ]);

        try {
            $uploadedFile = $request->file('image');
            
            // Create image manager with GD driver
            $manager = new ImageManager(new Driver());
            
            // Process and resize the image
            $image = $manager->read($uploadedFile);
            
            // Resize if too large (max width/height 1920px for featured images)
            if ($image->width() > 1920 || $image->height() > 1920) {
                $image->scaleDown(1920, 1920);
            }
            
            // Try WebP first, fallback to JPEG if WebP not supported
            $filename = Str::random(40);
            $storagePath = '';
            $compressedSize = 0;
            
            // Ensure directory exists
            $storageDir = storage_path('app/public/images/');
            if (!file_exists($storageDir)) {
                mkdir($storageDir, 0755, true);
            }
            
            try {
                // Try WebP encoding first (better compression)
                $filename .= '.webp';
                $storagePath = $storageDir . $filename;
                $image->toWebp(85)->save($storagePath);
                $compressedSize = filesize($storagePath);
            } catch (\Exception $webpError) {
                // Fallback to JPEG if WebP fails
                $filename = str_replace('.webp', '.jpg', $filename);
                $storagePath = $storageDir . $filename;
                $image->toJpeg(85)->save($storagePath);
                $compressedSize = filesize($storagePath);
            }
            
            return response()->json([
                'success' => true,
                'path' => '/storage/images/' . $filename,
                'filename' => $filename,
                'original_name' => $uploadedFile->getClientOriginalName(),
                'original_size' => $uploadedFile->getSize(),
                'compressed_size' => $compressedSize,
                'compression_ratio' => round((1 - ($compressedSize / $uploadedFile->getSize())) * 100, 1)
            ]);
        } catch (\Exception $e) {
            \Log::error('Image upload failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        try {
            // Remove /storage/ prefix to get actual storage path
            $storagePath = str_replace('/storage/', '', $request->path);
            
            if (Storage::disk('public')->exists($storagePath)) {
                Storage::disk('public')->delete($storagePath);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Image deleted successfully'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete image: ' . $e->getMessage()
            ], 500);
        }
    }
}
