<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use App\Models\Registration;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_events'        => Event::count(),
            'total_users'         => User::where('is_admin', false)->count(),
            'total_registrations' => Registration::count(),
            'upcoming_events'     => Event::where('status', 'upcoming')->count(),
        ];
        $recentEvents = Event::latest()->take(5)->get();
        $recentRegistrations = Registration::with(['user', 'event'])->latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recentEvents', 'recentRegistrations'));
    }
}