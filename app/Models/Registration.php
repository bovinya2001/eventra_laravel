<?php
namespace App\Models;

use App\Events\RegistrationCompleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Registration extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'payment_status',
        'payment_method',
        'payment_amount',
        'payment_reference',
        'stripe_payment_intent_id',
        'paid_at',
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'checked_in_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Registration $registration): void {
            $registration->uuid ??= (string) Str::uuid();
        });

        static::created(function (Registration $registration): void {
            if ($registration->canGeneratePass()) {
                RegistrationCompleted::dispatch($registration);
            }
        });
    }

    public function canGeneratePass(): bool
    {
        return $this->status === 'confirmed' && $this->payment_status === 'paid';
    }

    public function completePayment(string $method, ?string $reference = null): void
    {
        $this->forceFill([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_method' => $method,
            'payment_reference' => $reference ?: 'EVT-'.Str::upper(Str::random(12)),
            'paid_at' => now(),
        ])->save();

        RegistrationCompleted::dispatch($this->fresh());
    }

    public function user() { return $this->belongsTo(User::class); }
    public function event() { return $this->belongsTo(Event::class); }
}
