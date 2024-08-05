<?php

namespace Rias\StatamicLemonSqueezy\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ViewErrorBag;
use Rias\StatamicLemonSqueezy\Events\LicenseKeyCreated;
use Rias\StatamicLemonSqueezy\Events\LicenseKeyUpdated;
use Rias\StatamicLemonSqueezy\Events\OrderCreated;
use Rias\StatamicLemonSqueezy\Events\OrderRefunded;
use Rias\StatamicLemonSqueezy\Events\SubscriptionCancelled;
use Rias\StatamicLemonSqueezy\Events\SubscriptionCreated;
use Rias\StatamicLemonSqueezy\Events\SubscriptionExpired;
use Rias\StatamicLemonSqueezy\Events\SubscriptionPaused;
use Rias\StatamicLemonSqueezy\Events\SubscriptionPaymentFailed;
use Rias\StatamicLemonSqueezy\Events\SubscriptionPaymentRecovered;
use Rias\StatamicLemonSqueezy\Events\SubscriptionPaymentRefunded;
use Rias\StatamicLemonSqueezy\Events\SubscriptionPaymentSuccess;
use Rias\StatamicLemonSqueezy\Events\SubscriptionPlanChanged;
use Rias\StatamicLemonSqueezy\Events\SubscriptionResumed;
use Rias\StatamicLemonSqueezy\Events\SubscriptionUnpaused;
use Rias\StatamicLemonSqueezy\Events\SubscriptionUpdated;
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
            'subscription_updated' => SubscriptionUpdated::class,
            'subscription_cancelled' => SubscriptionCancelled::class,
            'subscription_resumed' => SubscriptionResumed::class,
            'subscription_expired' => SubscriptionExpired::class,
            'subscription_paused' => SubscriptionPaused::class,
            'subscription_unpaused' => SubscriptionUnpaused::class,
            'subscription_payment_failed' => SubscriptionPaymentFailed::class,
            'subscription_payment_success' => SubscriptionPaymentSuccess::class,
            'subscription_payment_recovered' => SubscriptionPaymentRecovered::class,
            'subscription_payment_refunded' => SubscriptionPaymentRefunded::class,
            'subscription_plan_changed' => SubscriptionPlanChanged::class,
            'license_key_created' => LicenseKeyCreated::class,
            'license_key_updated' => LicenseKeyUpdated::class,
        ];

        $eventName = $request->header('x-event-name');

        if (isset($events[$eventName])) {
            event($events[$eventName], $request->get('data'));
            Log::debug($eventName, $request->get('data'));
        } else {
            Log::error("Received unknown event name: {$eventName}");
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
