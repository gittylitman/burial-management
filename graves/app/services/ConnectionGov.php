<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ConnectionGov
{
    public function connectGovUrl()
    {
        $url = config('services.gov.url');
        $response = Http::get($url);
        $response = $response->json();

        return collect($response['result']['records']);
    }

    public function getCemeteryCities()
    {
        return $this->connectGovUrl()
            ->filter(function ($item) {
                return isset($item['SETL_NAME']) && $item['SETL_NAME'] !== null;
            })
            ->map(function ($item) {
                return [$item['SETL_NAME'] => $item['SETL_NAME']];
            })
            ->unique()
            ->values()
            ->collapse()
            ->all();
    }

    public function getCemeteryByCity($city)
    {
        return $this->connectGovUrl()
            ->filter(function ($record) use ($city) {
                return $record['SETL_NAME'] === $city;
            })
            ->map(function ($item) {
                return [$item['NAME'] => $item['NAME']];
            })
            ->values()
            ->collapse()
            ->all();
    }

    public function getCemeteryCoordinates($city, $cemetary_city)
    {
        $ORD = $this->connectGovUrl()
            ->where('SETL_NAME', $city)
            ->where('NAME', $cemetary_city)
            ->map(function ($item) {
                return ['E_ORD' => $item['E_ORD'], 'N_ORD' => $item['N_ORD']];
            })
            ->all();
        dump('https://www.google.com/maps/search/?api=1&query=' . $ORD['N_ORD'] . ',' . $ORD['E_ORD']);
        return 'https://www.google.com/maps/search/?api=1&query=' . $ORD['N_ORD'] . ',' . $ORD['E_ORD'];
    }
}
