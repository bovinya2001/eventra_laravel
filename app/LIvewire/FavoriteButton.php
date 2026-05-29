<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Favorite;
use Livewire\Component;

class FavoriteButton extends Component
{
    public Event $event;
    public bool $isFavorited = false;

    public function mount(Event $event): void
    {
        $this->event = $event;
        if (auth()->check()) {
            $this->isFavorited = Favorite::where('user_id', auth()->id())
                ->where('event_id', $event->id)
                ->exists();
        }
    }

    public function toggleFavorite(): void
    {
        if (!auth()->check()) {
            $this->redirect(route('login'));
            return;
        }

        if ($this->isFavorited) {
            Favorite::where('user_id', auth()->id())
                ->where('event_id', $this->event->id)
                ->delete();
        } else {
            Favorite::create([
                'user_id' => auth()->id(),
                'event_id' => $this->event->id,
            ]);
        }

        $this->isFavorited = !$this->isFavorited;
    }

    public function render()
    {
        return view('livewire.favorite-button');
    }
}
