@extends('statamic::layout')

@section('content')
    <div class="max-w-lg mt-2 mx-auto">
        <div class="rounded p-3 lg:px-7 lg:py-5 shadow bg-white">
            <header class="text-center text-grey">
                <h1 class="mb-3 text-black">{{ __('Connect your store') }}</h1>
                <p class="mb-4">{{ __('The Lemon Squeezy addon connects your Lemon Squeezy stores to your Statamic site to bring your products right into your content.') }}</p>

                <h2 class="mb-3 text-black">{{ __("1. Connect to Lemon Squeezy") }}</h2>
                <p class="mb-4">
                    {{ __('To get started, use the "Connect to Lemon Squeezy" button on the right. When prompted, click "Authorize" to connect your Lemon Squeezy account with this Statamic site.')}}
                </p>

                <h2 class="mb-3 text-black">
                    {{ __("2. Add the Lemon Squeezy field and start selling!") }}
                </h2>
                <p class="">
                    {{ __("To add products to your posts or pages, simply add the Lemon Squeezy field to your blueprints and select which product you’d like to insert when editing your content. Use the block settings to select a checkout link or a checkout overlay.") }}
                </p>
            </header>

            @if (isset($user) && $user)
                <div class="flex flex-col items-center mt-4">
                    <div class="relative w-24 h-24">
                        <svg
                                class="w-24 h-24"
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
                    <div class="text-center text-grey">
                        <p>Connected as</p>
                        <h3>{{ $user['attributes']['name'] }}</h3>
                        <div>{{ $user['attributes']['email'] }}</div>
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
            <div class="rounded mt-3 p-3 lg:px-7 lg:py-5 shadow bg-white">
                <h2 class="mb-2">Webhooks</h2>
                <p class="mb-2">Use the following <a href="https://app.lemonsqueezy.com/store/settings" target="_blank">settings</a> if you want to connect Lemon Squeezy webhooks to your Statamic site:</p>
                <h4>Callback URL:</h4>
                <p class="mb-2"><code>{{ route('lemon-squeezy.webhook') }} </code></p>
                <h4 class="mb-1">Signing secret:</h4>
                @if (config('statamic.lemon-squeezy.signing_secret'))
                    <p class="mb-2"><code>{{ config('statamic.lemon-squeezy.signing_secret') }}</code></p>
                @else
                    <p class="mb-1">You need to configure the signing secret</p>
                    <p class="mb-2">Make sure to publish the config file using <code>php artisan vendor:publish --tag=lemon-squeezy-config</code> and set it to a secure, random string</p>
                @endif
                <h4>Which updates:</h4>
                <p class="mb-2">This addon will map the following updates to Laravel events:</p>
                <h5>order_created</h5>
                <p class="mb-2"><code>{{ \Rias\StatamicLemonSqueezy\Events\OrderCreated::class }}</code></p>
                <h5>order_refunded</h5>
                <p class="mb-2"><code>{{ \Rias\StatamicLemonSqueezy\Events\OrderRefunded::class }}</code></p>
                <h5>subscription_created</h5>
                <p class="mb-2"><code>{{ \Rias\StatamicLemonSqueezy\Events\SubscriptionCreated::class }}</code></p>
                <h5>subscription_cancelled</h5>
                <p class="mb-2"><code>{{ \Rias\StatamicLemonSqueezy\Events\SubscriptionCancelled::class }}</code></p>
                <h5>subscription_resumed</h5>
                <p class="mb-2"><code>{{ \Rias\StatamicLemonSqueezy\Events\SubscriptionResumed::class }}</code></p>
                <h5>subscription_expired</h5>
                <p class="mb-2"><code>{{ \Rias\StatamicLemonSqueezy\Events\SubscriptionExpired::class }}</code></p>
                <h5>license_key_created</h5>
                <p class="mb-2"><code>{{ \Rias\StatamicLemonSqueezy\Events\LicenseKeyCreated::class }}</code></p>
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
