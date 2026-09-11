<?php

namespace Tests\Feature;

use App\Events\RegistrationCompleted;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event as EventFacade;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class RegistrationVenuePassTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_cannot_mark_a_registration_paid_through_a_web_endpoint(): void
    {
        [$user, $registration] = $this->pendingRegistration();

        $this->actingAs($user)->post('/registrations/'.$registration->id.'/payment', [
            'payment_method' => 'card',
            'payment_reference' => 'PAY-12345',
        ])->assertNotFound();

        $registration->refresh();
        $this->assertFalse($registration->canGeneratePass());
        $this->assertSame('pending', $registration->payment_status);
    }

    public function test_pass_is_unavailable_until_payment_is_complete(): void
    {
        [$user, $registration] = $this->pendingRegistration();

        $this->actingAs($user)
            ->get(route('registrations.pass', $registration))
            ->assertForbidden();
    }

    public function test_signed_qr_destination_verifies_the_attendee(): void
    {
        EventFacade::fake([RegistrationCompleted::class]);
        [$user, $registration] = $this->pendingRegistration();
        $registration->completePayment('bank_transfer', 'BANK-42');

        $url = URL::signedRoute('venue-pass.verify', ['uuid' => $registration->uuid]);

        $this->get($url)
            ->assertOk()
            ->assertSee($user->name)
            ->assertSee('Valid venue pass');
    }

    private function pendingRegistration(): array
    {
        $user = User::factory()->create();
        $event = Event::create([
            'title' => 'Paid Conference',
            'description' => 'A paid event.',
            'location' => 'Colombo',
            'event_date' => now()->addWeek(),
            'capacity' => 50,
            'price' => 2500,
            'status' => 'upcoming',
        ]);
        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'payment_amount' => $event->price,
        ]);

        return [$user, $registration];
    }
}
