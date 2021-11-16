<?php

namespace Rias\StatamicLemonSqueezy\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ViewErrorBag;
use Rias\StatamicLemonSqueezy\Events\LicenseKeyCreated;
use Rias\StatamicLemonSqueezy\Events\OrderCreated;
use Rias\StatamicLemonSqueezy\Events\OrderRefunded;
use Rias\StatamicLemonSqueezy\Events\SubscriptionCancelled;
use Rias\StatamicLemonSqueezy\Events\SubscriptionCreated;
use Rias\StatamicLemonSqueezy\Events\SubscriptionExpired;
use Rias\StatamicLemonSqueezy\Events\SubscriptionResumed;
use Rias\StatamicLemonSqueezy\Http\Controllers\Controller;
use Statamic\Support\Str;
use function abort;
use function abort_unless;
use function config;
use function event;
use function logger;
use function request;
use function response;

class LemonSqueezyWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->ensureValidSignature();

        $events = [
            'order_created' => OrderCreated::class,
            'order_refunded' => OrderRefunded::class,
            'subscription_created' => SubscriptionCreated::class,
            'subscription_cancelled' => SubscriptionCancelled::class,
            'subscription_resumed' => SubscriptionResumed::class,
            'subscription_expired' => SubscriptionExpired::class,
            'license_key_created' => LicenseKeyCreated::class,
        ];

        logger('Webhook');
        logger($request->all());

        $eventName = $request->header('x-event-name');

        if (isset($events[$eventName])) {
            logger('Triggering event ' . $eventName);
            event($events[$eventName], $request->get('data'));
        }

        return response(status: 204);
    }

    private function ensureValidSignature(): void
    {
        abort_unless(request()->hasHeader('x-signature'), 400);

        $secret    = config('statamic.lemon-squeezy.signing_secret');
        $payload   = file_get_contents('php://input');
        $hash      = hash_hmac('sha256', $payload, $secret);
        $signature = request()->header('x-signature');

        if ($hash !== $signature) {
            abort(400, 'Invalid signature');
        }
    }
}
