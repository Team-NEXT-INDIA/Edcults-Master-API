<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\GeneratedImage;

class ImageGenerationController extends Controller
{
    public function generateImage(Request $request)
    {
        $userId = $request->input('user_id');
        $prompt = $request->input('prompt');

        $stabilityApiKey = env('STABILITY_API_KEY');
        $outputFileName = 'edcults_' . uniqid() . '.png';
        $outputFile = 'public/generated_images/' . $outputFileName; // Updated storage path
        $baseUrl = 'https://api.stability.ai';
        $url = $baseUrl . '/v1/generation/stable-diffusion-v1-5/text-to-image';

        // Create the request payload
        $data = [
            'text_prompts' => [
                [
                    'text' => $prompt,
                ],
            ],
            'cfg_scale' => 7,
            'clip_guidance_preset' => 'FAST_BLUE',
            'height' => 512,
            'width' => 512,
            'samples' => 1,
            'steps' => 30,
        ];

        // Convert the payload to JSON
        $payload = json_encode($data);

        // Make the cURL request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: image/png',
            'Authorization: Bearer ' . $stabilityApiKey,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Store the response in the Laravel storage directory
        Storage::put($outputFile, $response);

        // Get the full URL of the image
        $imageUrl = url(Storage::url($outputFile));

        // Save the generated image details in the database
        $generatedImage = new GeneratedImage();
        $generatedImage->user_id = $userId;
        $generatedImage->prompt = $prompt;
        $generatedImage->image_url = $imageUrl;
        $generatedImage->save();

        return response()->json([
            'message' => 'Image generated successfully',
            'prompt' => $prompt,
            'user_id' => $userId,
            'image_url' => $imageUrl,
        ]);
    }
    public function deleteGeneratedImage(Request $request)
    {
        $userId = $request->input('user_id');
        $id = $request->input('id');

        // Find the generated image by ID and user_id
        $generatedImage = GeneratedImage::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        // If the image is found, delete it and return success response
        if ($generatedImage) {
            // Delete the image file from storage
            $imagePath = str_replace(url('/'), '', $generatedImage->image_url);
            Storage::delete($imagePath);

            // Delete the image record from the database
            $generatedImage->delete();

            return response()->json([
                'message' => 'Image deleted successfully',
            ]);
        }

        // If the image is not found or does not belong to the user, return error response
        return response()->json(
            [
                'error' =>
                    'Image not found or you do not have permission to delete it',
            ],
            404
        );
    }

    public function getUserGeneratedImages(Request $request)
    {
        $user_id = $request->input('user_id');

        // Get the generated images for the specified user_id
        $images = GeneratedImage::where('user_id', $user_id)->get();

        // Return the images in the response
        return response()->json([
            'message' => 'Images retrieved successfully',
            'user_id' => $user_id,
            'images' => $images,
        ]);
    }
}
