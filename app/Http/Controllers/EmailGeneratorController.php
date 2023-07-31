<?php

namespace App\Http\Controllers;
use App\Models\EmailGenerator;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Request;

class EmailGeneratorController extends Controller
{
    function generateEmail(Request $request)
    {
        $email_type = $request->input('email_type');
        $instructions = $request->input('instructions');
        $receiver_name = $request->input('receiver_name');
        $sender_organisation = $request->input('sender_organisation');
        $receiver_organisation = $request->input('receiver_organisation');
        $bprompt =
            'Write a Email with type of ' .
            $email_type .
            ' with the following instructions: ' .
            $instructions .
            ' and the receiver name is ' .
            $receiver_name .
            ' and the sender organisation is ' .
            $sender_organisation .
            ' and the receiver organisation is ' .
            $receiver_organisation .
            'make sure you use the above information in the email and if not necessary remove it.';

        $result = OpenAI::completions()->create([
            'max_tokens' => 4000,
            'model' => 'text-davinci-003',
            'prompt' => $bprompt,
        ]);
        $prompt = new EmailGenerator([
            'data' => $result,
        ]);
        return response()->json($prompt);
    }
}
