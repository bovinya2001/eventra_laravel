<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class WeatherForecastService
{
    public function forEvent(Event $event): ?array
    {
        if ($event->latitude === null || $event->longitude === null) return null;

        $days = today()->diffInDays($event->event_date->copy()->startOfDay(), false);
        if ($days < 0 || $days > 15) return null;

        $key = 'weather:'.round((float) $event->latitude, 3).':'.round((float) $event->longitude, 3);

        try {
            $daily = Cache::remember($key, now()->addMinutes(30), fn (): array => Http::baseUrl(config('services.open_meteo.url'))
                ->acceptJson()->timeout(5)->retry(2, 200)
                ->get('/v1/forecast', [
                    'latitude' => $event->latitude,
                    'longitude' => $event->longitude,
                    'daily' => 'weather_code,temperature_2m_max,temperature_2m_min,precipitation_probability_max,wind_speed_10m_max',
                    'timezone' => 'Asia/Colombo',
                    'forecast_days' => 16,
                ])->throw()->json('daily', []));
        } catch (Throwable $exception) {
            report($exception);
            return null;
        }

        $index = array_search($event->event_date->toDateString(), $daily['time'] ?? [], true);
        if ($index === false) return null;
        $code = $daily['weather_code'][$index] ?? null;

        return [
            'condition' => $this->condition($code),
            'temperature_max' => $daily['temperature_2m_max'][$index] ?? null,
            'temperature_min' => $daily['temperature_2m_min'][$index] ?? null,
            'rain_probability' => $daily['precipitation_probability_max'][$index] ?? null,
            'wind_speed_max' => $daily['wind_speed_10m_max'][$index] ?? null,
        ];
    }

    private function condition(?int $code): string
    {
        return match (true) {
            $code === 0 => 'Clear sky',
            in_array($code, [1, 2, 3], true) => 'Partly cloudy',
            in_array($code, [45, 48], true) => 'Foggy',
            $code !== null && $code >= 51 && $code <= 67 => 'Rain or drizzle',
            $code !== null && $code >= 80 && $code <= 82 => 'Rain showers',
            $code !== null && $code >= 95 => 'Thunderstorms',
            default => 'Mixed conditions',
        };
    }
}
