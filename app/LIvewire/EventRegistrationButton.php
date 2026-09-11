<?php
namespace App\Livewire;

use App\Models\Event;
use App\Models\Registration;
use Livewire\Component;

class EventRegistrationButton extends Component
{
    public Event $event;
    public bool $isRegistered = false;
    public string $message = '';

    public function mount(Event $event): void
    {
        $this->event = $event;
        $this->isRegistered = auth()->check()
            ? auth()->user()->events()->where('event_id', $event->id)->exists()
            : false;
    }

    public function register(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }
        if ($this->event->remaining_capacity <= 0) {
            $this->message = 'Sorry, this event is fully booked.';
            return;
        }
        if ($this->isRegistered) {
            $this->message = 'You are already registered.';
            return;
        }
        $isFree = (float) $this->event->price <= 0;
        $registration = Registration::create([
            'user_id'  => auth()->id(),
            'event_id' => $this->event->id,
            'status'   => $isFree ? 'confirmed' : 'pending',
            'payment_status' => $isFree ? 'paid' : 'pending',
            'payment_method' => $isFree ? 'free' : null,
            'payment_amount' => $this->event->price,
            'payment_reference' => $isFree ? 'FREE-'.$this->event->id.'-'.auth()->id() : null,
            'paid_at' => $isFree ? now() : null,
        ]);
        $this->isRegistered = true;
        $this->redirect(
            $registration->canGeneratePass()
                ? route('registrations.pass', $registration)
                : route('registrations.checkout', $registration),
            navigate: true,
        );
    }

    public function cancel(): void
    {
        Registration::where('user_id', auth()->id())
            ->where('event_id', $this->event->id)
            ->delete();
        $this->isRegistered = false;
        $this->message = 'Registration cancelled.';
        $this->event->refresh();
    }

    public function render()
    {
        return view('livewire.event-registration-button');
    }
}
