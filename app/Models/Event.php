<?php
namespace App\Models;

use App\Support\VenueLocations;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'description', 'location', 'location_key', 'latitude', 'longitude', 'event_date',
        'capacity', 'price', 'image', 'status'
    ];

    protected $casts = ['event_date' => 'datetime', 'latitude' => 'float', 'longitude' => 'float'];

    public function getMapEmbedUrlAttribute(): ?string
    {
        return $this->latitude === null || $this->longitude === null
            ? null
            : VenueLocations::embedUrl($this->latitude, $this->longitude);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'registrations');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function getRemainingCapacityAttribute()
    {
        return $this->capacity - $this->registrations()->count();
    }
}
