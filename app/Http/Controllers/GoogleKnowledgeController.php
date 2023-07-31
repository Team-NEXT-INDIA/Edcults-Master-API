<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleKnowledgeController extends Controller
{
    public function search(Request $request)
    {
        $api_key = env('GOOGLE_API_KEY');
        $service_url = 'https://kgsearch.googleapis.com/v1/entities:search';
        $company = $request->input('company_name');
        $params = [
            'query' => $company,
            'limit' => 10,
            'indent' => true,
            'key' => $api_key,
        ];
        $url = $service_url . '?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        curl_close($ch);

        return response()->json($response);
    }
}
