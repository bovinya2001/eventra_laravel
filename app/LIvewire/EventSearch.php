<?php
namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class EventSearch extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $sort = 'event_date';

    protected $queryString = ['search', 'status', 'sort'];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatus(): void { $this->resetPage(); }

    public function render()
    {
        $events = Event::query()
            ->where('status', 'upcoming')
            ->when($this->search, fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('location', 'like', "%{$this->search}%")
            )
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->orderBy($this->sort)
            ->paginate(9);

        return view('livewire.event-search', compact('events'));
    }
}