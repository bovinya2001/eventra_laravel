<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\VenuePassQrCode;
use Illuminate\Http\Request;

class RegistrationPaymentController extends Controller
{
    public function checkout(Request $request, Registration $registration)
    {
        $this->ensureOwner($request, $registration);
        $registration->load('event');

        if ($registration->canGeneratePass()) {
            return redirect()->route('registrations.pass', $registration);
        }

        return view('user.registrations.checkout', compact('registration'));
    }

    public function pass(Request $request, Registration $registration)
    {
        $this->ensureOwner($request, $registration);
        abort_unless($registration->canGeneratePass(), 403, 'Complete payment before generating a venue pass.');

        return view('user.registrations.pass', [
            'registration' => $registration->load(['user', 'event']),
        ]);
    }

    public function qr(Request $request, Registration $registration, VenuePassQrCode $qrCode)
    {
        $this->ensureOwner($request, $registration);
        abort_unless($registration->canGeneratePass(), 403, 'Venue pass is not available.');

        return response($qrCode->svg($registration), 200, [
            'Content-Type' => 'image/svg+xml',
            'Content-Disposition' => 'inline; filename="eventra-pass-'.$registration->uuid.'.svg"',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    public function verify(Request $request, string $uuid)
    {
        $registration = Registration::query()
            ->where('uuid', $uuid)
            ->with(['user', 'event'])
            ->firstOrFail();

        return view('venue-pass.verify', [
            'registration' => $registration,
            'isValid' => $registration->canGeneratePass(),
        ]);
    }

    private function ensureOwner(Request $request, Registration $registration): void
    {
        abort_unless($registration->user_id === $request->user()->id, 403);
    }
}
