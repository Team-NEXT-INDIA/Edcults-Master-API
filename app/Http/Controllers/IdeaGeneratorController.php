<?php

namespace App\Http\Controllers;
use OpenAI\Laravel\Facades\OpenAI;
use App\Models\IdeaGenerator;
use Illuminate\Http\Request;

class IdeaGeneratorController extends Controller
{
    function generateIdea(Request $request)
    {
        // $company = $request->input('company_name');
        $bprompt = "Generate a JSON representation of 15 Startup Ideas with the generated_ideas key and List of strings,
        and for every idea add emojis wherever necessary
        ";

        $result = OpenAI::completions()->create([
            'max_tokens' => 4000,
            'model' => 'text-davinci-003',
            'prompt' => $bprompt,
        ]);
        $prompt = new IdeaGenerator([
            'data' => $result,
        ]);
        return response()->json($prompt);
    }
}
