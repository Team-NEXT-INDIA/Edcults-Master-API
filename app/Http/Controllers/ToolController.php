<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function getTools()
    {
        $tools = Tool::all();

        return response()->json(['tools' => $tools]);
    }

    public function useTool(Request $request)
    {
        $userId = $request->input('user_id');
        $toolId = $request->input('tool_id');

        $user = User::where('user_id', $userId)->first();
        $tool = Tool::find($toolId);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if (!$tool) {
            return response()->json(['error' => 'Tool not found.'], 404);
        }

        $coins = $user->coins;

        if (!$coins) {
            return response()->json(
                ['error' => 'User does not have coins.'],
                400
            );
        }

        if ($coins->amount < $tool->amount) {
            return response()->json(['error' => 'Insufficient coins.'], 400);
        }

        // Subtract the tool amount from the user's coins
        $coins->amount -= $tool->amount;
        $coins->save();

        return response()->json(['message' => 'Tool used successfully.']);
    }
}
