<?php

namespace App\Services;

use App\Models\Registration;
use RuntimeException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;

class StripePaymentService
{
    private StripeClient $client;

    public function __construct()
    {
        $secret = config('services.stripe.secret');
        if (!$secret) {
            throw new RuntimeException('Stripe secret key is not configured.');
        }

        $this->client = new StripeClient($secret);
    }

    public function createOrRetrieveIntent(Registration $registration): PaymentIntent
    {
        if ($registration->stripe_payment_intent_id) {
            return $this->retrieve($registration->stripe_payment_intent_id);
        }

        $registration->loadMissing(['event', 'user']);
        $amount = $this->amountInMinorUnits($registration);

        $intent = $this->client->paymentIntents->create([
            'amount' => $amount,
            'currency' => 'lkr',
            'payment_method_types' => ['card'],
            'description' => 'Eventra registration: '.$registration->event->title,
            'receipt_email' => $registration->user->email,
            'metadata' => [
                'registration_id' => (string) $registration->id,
                'registration_uuid' => $registration->uuid,
                'user_id' => (string) $registration->user_id,
            ],
        ], [
            'idempotency_key' => 'eventra-registration-'.$registration->uuid,
        ]);

        $registration->forceFill([
            'stripe_payment_intent_id' => $intent->id,
            'payment_method' => 'stripe',
        ])->save();

        return $intent;
    }

    public function retrieve(string $paymentIntentId): PaymentIntent
    {
        return $this->client->paymentIntents->retrieve($paymentIntentId, []);
    }

    public function matchesRegistration(PaymentIntent $intent, Registration $registration): bool
    {
        return $intent->status === 'succeeded'
            && $intent->currency === 'lkr'
            && $intent->amount === $this->amountInMinorUnits($registration)
            && $intent->amount_received === $this->amountInMinorUnits($registration)
            && ($intent->metadata->registration_uuid ?? null) === $registration->uuid
            && (!$registration->stripe_payment_intent_id || $registration->stripe_payment_intent_id === $intent->id);
    }

    public function amountInMinorUnits(Registration $registration): int
    {
        return (int) round((float) $registration->payment_amount * 100);
    }
}
