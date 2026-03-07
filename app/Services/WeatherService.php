<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $baseUrl = 'https://wttr.in/';

    /**
     * Get weather data for a specific city.
     *
     * @param string $city
     * @return array|null
     */
    public function getWeather(string $city = 'Gaza')
    {
        return Cache::remember("weather_{$city}", 1800, function () use ($city) {
            try {
                $response = Http::get("{$this->baseUrl}{$city}?format=j1");

                if ($response->successful()) {
                    $data = $response->json();
                    $current = $data['current_condition'][0];
                    
                    return [
                        'temp' => $current['temp_C'],
                        'condition' => $this->translateCondition($current['weatherDesc'][0]['value']),
                        'humidity' => $current['humidity'],
                        'wind_speed' => $current['windspeedKmph'],
                        'precip' => $current['precipMM'],
                        'city' => $city === 'Gaza' ? 'غزة' : $city,
                        'time' => now()->format('H:i'),
                    ];
                }
            } catch (\Exception $e) {
                \Log::error("Weather API Error: " . $e->getMessage());
            }

            return null;
        });
    }

    /**
     * Translate weather description to Arabic.
     * Simple mapping for common states.
     *
     * @param string $desc
     * @return string
     */
    protected function translateCondition(string $desc)
    {
        $desc = strtolower($desc);
        $map = [
            'clear' => 'صافي',
            'sunny' => 'مشمس',
            'partly cloudy' => 'غائم جزئياً',
            'cloudy' => 'غائم',
            'overcast' => 'غائم كلياً',
            'mist' => 'ضباب خفيف',
            'fog' => 'ضباب',
            'patchy rain possible' => 'احتمال مطر خفيف',
            'patchy rain nearby' => 'أمطار متفرقة قريبة',
            'light rain' => 'مطر خفيف',
            'moderate rain' => 'مطر متوسط',
            'heavy rain' => 'مطر غزير',
            'thundery outbreaks possible' => 'احتمال عواصف رعدية',
            'thunderstorm' => 'عاصفة رعدية',
        ];

        return $map[$desc] ?? $desc;
    }
}
