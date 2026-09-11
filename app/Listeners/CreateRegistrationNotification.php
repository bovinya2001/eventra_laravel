<?php

namespace App\Listeners;

use App\Events\RegistrationCompleted;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateRegistrationNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public int $tries = 3;

    public bool $deleteWhenMissingModels = true;

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [5, 30, 60];
    }

    public function handle(RegistrationCompleted $event): void
    {
        $registration = $event->registration->loadMissing('event');

        Notification::updateOrCreate([
            'user_id' => $registration->user_id,
            'event_id' => $registration->event_id,
            'type' => 'registration_confirmation',
        ], [
            'title' => 'Registration Confirmed',
            'message' => "Payment received. Your venue pass for {$registration->event->title} is ready.",
            'icon' => 'check-circle',
            'color' => 'green',
        ]);
    }
}
