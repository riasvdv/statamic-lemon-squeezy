<?php

namespace Rias\StatamicLemonSqueezy\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ProductsController extends Controller
{
    public function __invoke()
    {
        $token = $this->getToken();

        if (! $token) {
            return response()->json([
                'error' => __( 'Unauthorized request'),
            ], 401);
        }

        $store_id = request('store_id');

        if (! $store_id) {
            return response()->json([
                'error' => __( 'Store id is required'),
            ], 400);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/vnd.api+json',
            'Content-Type'  => 'application/vnd.api+json',
            'Cache-Control' => 'no-cache',
        ])->get($this->api_url . "/v1/stores/{$store_id}/products");

        if (! $response->successful()) {
            return response()->json([
                'error' => $response->json('error'),
            ], 400);
        }

        $product_data = $response->json('data');

        $products = [];
        foreach ( $product_data as $product ) {
            $products[] = [
                'label' => $product['attributes']['name'],
                'value' => $product['attributes']['buy_now_url'],
            ];
        }

        return response()->json($products);
    }
}
