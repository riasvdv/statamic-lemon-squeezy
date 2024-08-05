<?php

namespace Rias\StatamicLemonSqueezy\Http\Controllers;

use http\Message;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Statamic\Support\Str;

class LemonSqueezyController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('lemon squeezy');

        $user = null;

        if($token = $this->getToken()) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/vnd.api+json',
                'Cache-Control' => 'no-cache',
            ])->get($this->api_url . '/v1/users/me');

            if (! $response->successful()) {
                $errors = new ViewErrorBag();
                $errors->add('oauth', $response->body());

                return view('lemon-squeezy::index', compact('errors'));
            }

            $user = $response->json('data');
        }

        return view('lemon-squeezy::index', [
            'title' => 'Lemon Squeezy',
            'user' => $user,
        ]);
    }

    public function oauth(Request $request): RedirectResponse
    {
        if (! $request->session()->has('lsq_oauth_code')) {
            $request->session()->put('lsq_oauth_code', Str::random(40));
        }

        if (! $request->session()->has('lsq_oauth_code_verifier')) {
            $request->session()->put('lsq_oauth_code_verifier', Str::random(128));
        }

        $code_challenge = strtr(
            rtrim(
                base64_encode(hash('sha256', session('lsq_oauth_code_verifier'), true)),
                '='
            ),
            '+/',
            '-_'
        );

        $query = http_build_query(
            array(
                'client_id' => $this->client_id,
                'redirect_uri' => cp_route('lemon-squeezy.callback'),
                'response_type' => 'code',
                'scope' => '',
                'state' => session('lsq_oauth_code'),
                'code_challenge' => $code_challenge,
                'code_challenge_method' => 'S256',
            )
        );

        return redirect()->to($this->app_url . '/oauth/authorize?' . $query);
    }

    public function disconnect(): RedirectResponse
    {
        $this->authorize('lemon squeezy');

        File::delete(base_path('lemon-squeezy.json'));

        return redirect(cp_route('lemon-squeezy'));
    }

    public function callback(Request $request): RedirectResponse
    {
        if ($request->has('error')) {
            return redirect()->to(cp_route('lemon-squeezy'))->withErrors([
                'oauth' => $request->get('error'),
            ]);
        }

        if (! $request->session()->has('lsq_oauth_code') || ! $request->session()->has('lsq_oauth_code_verifier')) {
            abort('401');
        }

        $code = $request->code;
        $state = $request->state;

        if (session('lsq_oauth_code') !== $state || !$code ) {
            return redirect()->to(cp_route('lemon-squeezy'))->withErrors([
                'oauth' => __('Invalid oauth state/code'),
            ]);
        }

        $response = Http::post($this->app_url . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $this->client_id,
            'redirect_uri' => cp_route('lemon-squeezy.callback'),
            'code_verifier' => session('lsq_oauth_code_verifier'),
            'code' => $code,
        ]);

        if (! $response->successful()) {
            return redirect()->to(cp_route('lemon-squeezy'))->withErrors([
                'oauth' => $response->body(),
            ]);
        }

        $data = $response->json();

        File::put(storage_path('statamic/lemon-squeezy.json'), json_encode($data));

        return redirect()->to(cp_route('lemon-squeezy'));
    }
}
