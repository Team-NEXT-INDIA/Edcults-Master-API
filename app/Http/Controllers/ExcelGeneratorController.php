<?php

namespace App\Http\Controllers;
use App\Models\ExcelGenerator;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;

class ExcelGeneratorController extends Controller
{
    function generateFormula(Request $request)
    {
        $query = $request->input('query');

        $bprompt = "Give me Excel formula for the below query: {$query}. Don't use any example, just give me direct formula.";

        $result = OpenAI::completions()->create([
            'max_tokens' => 4000,
            'model' => 'text-davinci-003',
            'prompt' => $bprompt,
        ]);
        $prompt = new ExcelGenerator([
            'data' => $result,
        ]);
        return response()->json($prompt);
    }
}
