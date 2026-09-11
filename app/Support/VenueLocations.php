<?php

namespace App\Support;

use InvalidArgumentException;

class VenueLocations
{
    public static function all(): array
    {
        return config('venues.locations', []);
    }

    public static function find(string $key): array
    {
        return self::all()[$key] ?? throw new InvalidArgumentException('Unknown venue location.');
    }

    public static function embedUrl(float $latitude, float $longitude): string
    {
        $offset = 0.025;
        $bbox = implode(',', [$longitude - $offset, $latitude - $offset, $longitude + $offset, $latitude + $offset]);

        return 'https://www.openstreetmap.org/export/embed.html?'.http_build_query([
            'bbox' => $bbox,
            'layer' => 'mapnik',
            'marker' => $latitude.','.$longitude,
        ]);
    }
}
