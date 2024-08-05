@extends('statamic::layout')

@section('content')
    <div class="max-w-lg mt-2 mx-auto">
        <div class="rounded p-3 lg:px-7 lg:py-5 shadow bg-white">
            <header class="text-center text-grey">
                <div class="text-center mb-4">
                    <svg width="45px" viewBox="0 0 128 174" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M43.2867 106.795L90.2155 128.375C96.0319 131.051 100.138 135.542 102.355 140.694C107.963 153.739 100.298 167.081 88.2658 171.879C76.2316 176.676 63.4061 173.589 57.5744 160.023L37.1511 112.394C35.5684 108.702 39.5441 105.074 43.2867 106.795" fill="#FFC233" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M46.1054 92.8264L94.548 74.6126C110.648 68.5591 128.235 80.0127 127.998 96.6547C127.994 96.8719 127.99 97.0891 127.984 97.3081C127.636 113.514 110.539 124.406 94.7927 118.673L46.1513 100.965C42.2711 99.5535 42.2425 94.2786 46.1054 92.8264" fill="#FFC233" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M43.39 86.5175L91.0107 66.3914C106.835 59.7029 110.851 39.6283 98.4575 28.0294C98.2951 27.8766 98.1326 27.7257 97.9682 27.5747C85.8174 16.3567 65.7305 20.3065 58.8131 35.0822L37.4436 80.7328C35.7386 84.3733 39.592 88.1225 43.39 86.5175" fill="#FFC233" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M31.1356 78.5642L48.4491 31.3456C50.5956 25.4909 50.198 19.5202 47.9789 14.3686C42.3594 1.32848 27.1408 -2.8808 15.1104 1.92481C3.08191 6.73226 -3.71504 17.6502 2.12812 31.2112L22.6853 78.7888C24.2794 82.4753 29.7556 82.3299 31.1356 78.5642" fill="#FFC233" />
                    </svg>
                </div>
                @unless (isset($user) && $user)
                <h1 class="mb-3 text-black">{{ __('Connect your store') }}</h1>
                <p class="mb-8">{{ __('The Lemon Squeezy addon connects your Lemon Squeezy stores to your Statamic site to bring your products right into your content.') }}</p>

                <h2 class="mb-3 text-black">{{ __("1. Connect to Lemon Squeezy") }}</h2>
                <p class="mb-8">
                    {{ __('To get started, use the "Connect to Lemon Squeezy" button below. When prompted, click "Authorize" to connect your Lemon Squeezy account with this Statamic site.')}}
                </p>

                <h2 class="mb-3 text-black">
                    {{ __("2. Add the Lemon Squeezy field and start selling!") }}
                </h2>
                @endunless
                <p class="mb-4">
                    {{ __("To add products to your posts or pages, simply add the Lemon Squeezy field to your blueprints and select which product you’d like to insert when editing your content. Use the block settings to select a checkout link or a checkout overlay.") }}
                </p>
                <p v-pre>{!! __('Afterwards you can add the field to your template using <code>&lbrace;&lbrace; lemon_squeezy_field &rbrace;&rbrace;</code>. It will automatically be augmented based on your settings.') !!}</p>
            </header>

            @if (isset($user) && $user)
                <div class="flex flex-col items-center mt-4">
                    <div class="relative w-12 h-12">
                        <svg
                                class="w-12 h-12"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 80 80"
                                version="1.1"
                        >
                            <circle fill="{{ $user['attributes']['color'] }}" width="80" height="80" cx="40" cy="40" r="40" />
                            <text class="text-white" x="50%" y="50%" alignmentBaseline="middle" textAnchor="middle" fontSize="32" fontWeight="400" dy=".1em" dominantBaseline="middle" fill="#ffffff">
                                {{ strtoupper(substr($user['attributes']['name'], 0, 1)) }}
                            </text>
                        </svg>
                        @if ($user['attributes']['avatar_url'] ?? null)
                            <img class="absolute inset-0 w-full h-full rounded-full" src="{{ $user['attributes']['avatar_url'] }}" alt="{{ $user['attributes']['name'] }}" />
                        @endif
                    </div>
                    <div class="text-center text-grey mt-2">
                        <p>Connected as</p>
                        <span class="font-bold">{{ $user['attributes']['name'] }}</span>
                        <span class="font-bold">{{ $user['attributes']['email'] }}</span>
                    </div>
                </div>
            @endif

            @error('oauth')
                <div class="bg-red-50">
                    An error occurred:&nbsp;{{ $message }}
                </div>
            @enderror
        </div>

        @if (isset($user) && $user)
            <div class="rounded mt-6 p-3 lg:px-7 lg:py-5 shadow bg-white">
                <h2 class="mb-4">Webhooks</h2>
                <p class="mb-4">Use the following <a href="https://app.lemonsqueezy.com/store/settings" target="_blank">settings</a> if you want to connect Lemon Squeezy webhooks to your Statamic site:</p>
                <h4>Callback URL:</h4>
                <p class="mb-4"><code>{{ route('lemon-squeezy.webhook') }} </code></p>
                <h4 class="mb-1">Signing secret:</h4>
                @if (config('statamic.lemon-squeezy.signing_secret'))
                    <p class="mb-4"><code>{{ config('statamic.lemon-squeezy.signing_secret') }}</code></p>
                @else
                    <p class="mb-1">You need to configure the signing secret</p>
                    <p class="mb-4">Make sure to publish the config file using <code>php artisan vendor:publish --tag=lemon-squeezy-config</code> and set it to a secure, random string</p>
                @endif
                <h4>Which updates:</h4>
                <p class="mb-4">This addon will map the following updates to Laravel events:</p>
                <h5>order_created</h5>
                <p class="mb-4"><code>{{ \Rias\StatamicLemonSqueezy\Events\OrderCreated::class }}</code></p>
                <h5>order_refunded</h5>
                <p class="mb-4"><code>{{ \Rias\StatamicLemonSqueezy\Events\OrderRefunded::class }}</code></p>
                <h5>subscription_created</h5>
                <p class="mb-4"><code>{{ \Rias\StatamicLemonSqueezy\Events\SubscriptionCreated::class }}</code></p>
                <h5>subscription_cancelled</h5>
                <p class="mb-4"><code>{{ \Rias\StatamicLemonSqueezy\Events\SubscriptionCancelled::class }}</code></p>
                <h5>subscription_resumed</h5>
                <p class="mb-4"><code>{{ \Rias\StatamicLemonSqueezy\Events\SubscriptionResumed::class }}</code></p>
                <h5>subscription_expired</h5>
                <p class="mb-4"><code>{{ \Rias\StatamicLemonSqueezy\Events\SubscriptionExpired::class }}</code></p>
                <h5>license_key_created</h5>
                <p class="mb-4"><code>{{ \Rias\StatamicLemonSqueezy\Events\LicenseKeyCreated::class }}</code></p>
            </div>
        @endif

        <div class="flex justify-center mt-4">
            @if (isset($user) && $user)
                <form action="{{ cp_route('lemon-squeezy.oauth') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn-primary mx-auto btn-lg">{{ __('Disconnect from Lemon Squeezy') }}</button>
                </form>
            @else
                <form action="{{ cp_route('lemon-squeezy.oauth') }}" method="POST">
                    @csrf
                    <button class="btn-primary mx-auto btn-lg">{{ __('Connect to Lemon Squeezy') }}</button>
                </form>
            @endif
        </div>
    </div>
@endsection
