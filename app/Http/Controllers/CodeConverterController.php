<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\CodeConverter;
use OpenAI\Exceptions\ErrorException;

class CodeConverterController extends Controller
{
    public function generateResult(Request $request)
    {
        try {
            $messages = $request->input('messages');

            // Build the prompt from the messages
            $prompt = '';
            foreach ($messages as $message) {
                $role = $message['role'];
                $content = $message['content'];
                $prompt .= "$role: $content\n";
            }

            $result = OpenAI::completions()->create([
                'model' => 'text-davinci-003',
                'prompt' => $prompt,
                'max_tokens' => 4000,
            ]);

            $codeConverter = new CodeConverter([
                'code' => $prompt,
                'data' => $result,
            ]);

            return response()->json($codeConverter);
        } catch (ErrorException $e) {
            // Handle the error, e.g., log it or return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
