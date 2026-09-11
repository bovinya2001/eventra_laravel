<?php

namespace Tests\Feature;

use App\Events\RegistrationCompleted;
use App\Listeners\CreateRegistrationNotification;
use App\Models\Event as EventModel;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueuedRegistrationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_registration_dispatches_the_domain_event(): void
    {
        Event::fake([RegistrationCompleted::class]);

        $registration = $this->createRegistration();

        Event::assertDispatched(
            RegistrationCompleted::class,
            fn (RegistrationCompleted $event): bool => $event->registration->is($registration),
        );
    }

    public function test_registration_notification_listener_is_queued(): void
    {
        Queue::fake();

        $this->createRegistration();

        Queue::assertPushed(
            CallQueuedListener::class,
            fn (CallQueuedListener $job): bool => $job->class === CreateRegistrationNotification::class,
        );
    }

    public function test_listener_creates_the_registration_notification(): void
    {
        Event::fake([RegistrationCompleted::class]);
        $registration = $this->createRegistration();

        (new CreateRegistrationNotification())->handle(new RegistrationCompleted($registration));

        $this->assertDatabaseHas('notifications', [
            'user_id' => $registration->user_id,
            'event_id' => $registration->event_id,
            'type' => 'registration_confirmation',
            'title' => 'Registration Confirmed',
        ]);
    }

    private function createRegistration(): Registration
    {
        $user = User::factory()->create();
        $event = EventModel::create([
            'title' => 'Laravel Community Meetup',
            'description' => 'A community event.',
            'location' => 'Colombo',
            'event_date' => now()->addWeek(),
            'capacity' => 100,
            'price' => 0,
            'status' => 'upcoming',
        ]);

        return Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => 'free',
            'payment_amount' => 0,
            'payment_reference' => 'TEST-FREE',
            'paid_at' => now(),
        ]);
    }
}
