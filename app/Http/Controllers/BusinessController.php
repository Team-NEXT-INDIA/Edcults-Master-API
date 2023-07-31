<?php

namespace App\Http\Controllers;
use App\Models\BusinessGenerator;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    function generateResult(Request $request)
    {
        $company = $request->input('company_name');
        $bprompt = "Generate a JSON representation of the {$company} with the following keys and data types: - company_name: String- description: String- expertise: List of Strings- latest_news: List of Strings- recent_projects: String- clients: List of Strings- awards: List of Strings- key_personnel: Map of String keys and String values- competitors: List of Strings";

        $result = OpenAI::completions()->create([
            'max_tokens' => 4000,
            'model' => 'text-davinci-003',
            'prompt' => $bprompt,
        ]);
        $prompt = new BusinessGenerator([
            'data' => $result,
        ]);
        return response()->json($prompt);
    }
}
