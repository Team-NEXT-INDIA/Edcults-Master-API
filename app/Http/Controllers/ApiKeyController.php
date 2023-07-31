<?php
namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function getApiKey(Request $request)
    {
        $request->validate([
            'provider_name' => 'required|string',
        ]);

        $providerName = $request->input('provider_name');
        $apiKey = ApiKey::where('provider_name', $providerName)->value(
            'api_key'
        );

        if ($apiKey) {
            return response()->json(['api_key' => $apiKey]);
        } else {
            return response()->json(
                ['message' => 'API key not found for the provider'],
                404
            );
        }
    }

    public function setApiKey(Request $request)
    {
        $request->validate([
            'provider_name' => 'required|string',
            'api_key' => 'required|string',
        ]);

        $providerName = $request->input('provider_name');
        $apiKey = $request->input('api_key');

        ApiKey::updateOrCreate(
            ['provider_name' => $providerName],
            ['api_key' => $apiKey]
        );

        return response()->json(['message' => 'API key set successfully']);
    }
}
