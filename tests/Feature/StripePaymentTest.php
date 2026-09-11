<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use App\Services\StripePaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Stripe\PaymentIntent;
use Tests\TestCase;

class StripePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_a_stripe_payment_intent(): void
    {
        [$user, $registration] = $this->pendingRegistration();
        $intent = PaymentIntent::constructFrom([
            'id' => 'pi_test_123',
            'object' => 'payment_intent',
            'client_secret' => 'pi_test_123_secret_test',
        ]);
        $stripe = Mockery::mock(StripePaymentService::class);
        $stripe->shouldReceive('createOrRetrieveIntent')->once()->withArgs(fn ($value) => $value->is($registration))->andReturn($intent);
        $this->app->instance(StripePaymentService::class, $stripe);

        $this->actingAs($user)
            ->postJson(route('stripe.intent', $registration))
            ->assertOk()
            ->assertJson(['client_secret' => 'pi_test_123_secret_test']);
    }

    public function test_signed_stripe_webhook_completes_matching_registration(): void
    {
        [, $registration] = $this->pendingRegistration();
        config(['services.stripe.webhook_secret' => 'whsec_test_secret']);
        $payload = json_encode([
            'id' => 'evt_test_123',
            'object' => 'event',
            'type' => 'payment_intent.succeeded',
            'data' => ['object' => [
                'id' => 'pi_test_456',
                'object' => 'payment_intent',
                'status' => 'succeeded',
                'amount' => 250000,
                'amount_received' => 250000,
                'currency' => 'lkr',
                'metadata' => ['registration_uuid' => $registration->uuid],
            ]],
        ], JSON_THROW_ON_ERROR);
        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, 'whsec_test_secret');
        $stripe = Mockery::mock(StripePaymentService::class);
        $stripe->shouldReceive('matchesRegistration')->once()->andReturnTrue();
        $this->app->instance(StripePaymentService::class, $stripe);

        $this->call('POST', route('stripe.webhook'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_STRIPE_SIGNATURE' => 't='.$timestamp.',v1='.$signature,
        ], $payload)->assertOk()->assertJson(['received' => true]);

        $registration->refresh();
        $this->assertSame('paid', $registration->payment_status);
        $this->assertSame('confirmed', $registration->status);
        $this->assertSame('stripe', $registration->payment_method);
        $this->assertSame('pi_test_456', $registration->stripe_payment_intent_id);
    }

    private function pendingRegistration(): array
    {
        $user = User::factory()->create();
        $event = Event::create([
            'title' => 'Stripe Test Event',
            'description' => 'Stripe integration test.',
            'location' => 'Colombo',
            'event_date' => now()->addWeek(),
            'capacity' => 20,
            'price' => 2500,
            'status' => 'upcoming',
        ]);
        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_amount' => 2500,
        ]);

        return [$user, $registration];
    }
}
