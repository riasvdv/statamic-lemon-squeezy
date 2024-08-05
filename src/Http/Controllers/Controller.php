<?php

namespace Rias\StatamicLemonSqueezy\Http\Controllers;

use Illuminate\Support\Facades\File;
use Throwable;

abstract class Controller
{
    protected string $client_id = '94d9c7dd-610f-4070-993f-20b5ba3caf75';
    protected string $app_url = 'https://app.lemonsqueezy.com';
    protected string $api_url = 'https://api.lemonsqueezy.com';

    protected function getToken(): ?string
    {
        try {
            $data = json_decode(File::get(storage_path('statamic/lemon-squeezy.json')), true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            $data = null;
        }

        return $data['access_token'] ?? null;
    }
}
