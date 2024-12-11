<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ConnectGov
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
        $filtered_names = $this->connectGovUrl()
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

        return $filtered_names;
    }

    public function getCemeteryByCity($city)
    {
        $filtered_names = $this->connectGovUrl()
            ->filter(function ($record) use ($city) {
                return $record['SETL_NAME'] === $city;
            })
            ->map(function ($item) {
                return [$item['NAME'] => $item['NAME']];
            })
            ->values()
            ->collapse()
            ->all();

        return $filtered_names;
    }
}
