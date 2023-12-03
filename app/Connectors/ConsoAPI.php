<?php

namespace App\Connectors;

use Illuminate\Support\Facades\Http;

class ConsoAPI
{
    public static function getDailyConsumption($startDate, $endDate = null)
    {
        if (is_null($endDate)) {
            $endDate = now();
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('API_KEY_CONSO'),
            'User-Agent' => 'Dashboard Maison',
            'From' => 'laurent@c0chett0.dev',
        ])
            ->get(
                env('API_URL_CONSO') . 'daily_consumption',
                [
                    'prm' => env('PRM_ID'),
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ]
            );

        return $response->json('interval_reading');
    }

    public static function getConsumptionMaxPower($startDate, $endDate = null)
    {
        if (is_null($endDate)) {
            $endDate = now();
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('API_KEY_CONSO'),
            'User-Agent' => 'Dashboard Maison',
            'From' => 'laurent@c0chett0.dev',
        ])
            ->get(
                env('API_URL_CONSO') . 'consumption_max_power',
                [
                    'prm' => env('PRM_ID'),
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ]
            );

        return $response->json('interval_reading');
    }
}
