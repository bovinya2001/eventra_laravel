<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationBell extends Component
{
    public $unreadCount = 0;
    public $notifications = [];
    public $showDropdown = false;

    #[\Livewire\Attributes\On('notification-added')]
    public function refreshNotifications()
    {
        if (auth()->check()) {
            $this->unreadCount = auth()->user()->unreadNotifications()->count();
            $this->notifications = auth()->user()->notifications()->take(5)->get();
        }
    }

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            $this->refreshNotifications();
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        $this->refreshNotifications();
    }

    public function deleteNotification($notificationId)
    {
        auth()->user()->notifications()->where('id', $notificationId)->delete();
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
