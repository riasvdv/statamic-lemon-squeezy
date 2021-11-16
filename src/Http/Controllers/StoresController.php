<?php

namespace Rias\StatamicLemonSqueezy\Http\Controllers;

use Illuminate\Support\Facades\Http;

class StoresController extends Controller
{
    public function __invoke()
    {
        $token = $this->getToken();

        if (! $token) {
            return response()->json([
                'error' => __("Uh oh! Looks like you haven't connected your store yet! Please visit the Lemon Squeezy Settings and connect to Lemon Squeezy."),
            ], 401);
        }

        // Get stores.
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/vnd.api+json',
            'Content-Type'  => 'application/vnd.api+json',
            'Cache-Control' => 'no-cache',
        ])->get($this->api_url . '/v1/stores/');

        if (! $response->successful()) {
            return response()->json([
                'error' => $response->json('error'),
            ], 400);
        }

        $store_data = $response->json('data');

        $stores = [];
        foreach ($store_data as $store) {
            $stores[] = [
                'label' => $store['attributes']['name'],
                'value' => $store['id'],
            ];
        }

        return response()->json($stores);
    }
}
