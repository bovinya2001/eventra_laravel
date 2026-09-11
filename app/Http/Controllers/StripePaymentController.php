<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\StripePaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Throwable;

class StripePaymentController extends Controller
{
    public function intent(Request $request, Registration $registration, StripePaymentService $stripe)
    {
        $this->ensureOwner($request, $registration);
        abort_if($registration->payment_status === 'paid', 422, 'Registration is already paid.');

        $intent = $stripe->createOrRetrieveIntent($registration);

        return response()->json(['client_secret' => $intent->client_secret]);
    }

    public function completed(Request $request, Registration $registration, StripePaymentService $stripe)
    {
        $this->ensureOwner($request, $registration);
        $paymentIntentId = $request->string('payment_intent')->toString();
        abort_unless($paymentIntentId && $registration->stripe_payment_intent_id === $paymentIntentId, 403);

        $intent = $stripe->retrieve($paymentIntentId);
        if ($intent->status === 'succeeded') {
            $this->completeFromStripe($registration, $intent, $stripe);
            return redirect()->route('registrations.pass', $registration)
                ->with('success', 'Stripe payment confirmed. Your venue pass is ready.');
        }

        return redirect()->route('registrations.checkout', $registration)
            ->with('error', 'Stripe is still processing the payment. Please try again shortly.');
    }

    public function webhook(Request $request, StripePaymentService $stripe)
    {
        $secret = config('services.stripe.webhook_secret');
        if (!$secret) {
            return response()->json(['message' => 'Stripe webhook secret is not configured.'], 503);
        }

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                (string) $request->header('Stripe-Signature'),
                $secret,
            );
        } catch (Throwable) {
            return response()->json(['message' => 'Invalid Stripe webhook signature.'], 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            /** @var PaymentIntent $intent */
            $intent = $event->data->object;
            $registration = Registration::query()
                ->where('uuid', $intent->metadata->registration_uuid ?? '')
                ->first();

            if ($registration) {
                $this->completeFromStripe($registration, $intent, $stripe);
            }
        }

        return response()->json(['received' => true]);
    }

    private function completeFromStripe(Registration $registration, PaymentIntent $intent, StripePaymentService $stripe): void
    {
        abort_unless($stripe->matchesRegistration($intent, $registration), 422, 'Stripe payment does not match registration.');

        DB::transaction(function () use ($registration, $intent): void {
            $locked = Registration::query()->lockForUpdate()->findOrFail($registration->id);
            if ($locked->payment_status === 'paid') return;

            $locked->forceFill(['stripe_payment_intent_id' => $intent->id])->save();
            $locked->completePayment('stripe', $intent->id);
        });
    }

    private function ensureOwner(Request $request, Registration $registration): void
    {
        abort_unless($registration->user_id === $request->user()->id, 403);
    }
}
