<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiIntegrationController extends Controller
{
    public function fetchData()
    {
        $response = Http::get('https://jsonplaceholder.typicode.com/posts/1');

        if ($response->successful()) {
            return response()->json([
                'status' => 'success',
                'data' => $response->json()
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to fetch data'
        ], 500);
    }
}
