<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GeolocationService
{
    public static function getLatLngByAddress($address, $number, $district, $city, $uf)
    {
        $URL = env("GOOGLE_GEOCODE_URL");
        $API_KEY = env("GOOGLE_API_KEY");
        $lat_lng_null = ["lat" => null, "lng" => null];

        $http = new Client();

        $formattedAddress = implode(",", [
            $address . " " . $number,
            $district,
            $city,
            strtoupper($uf),
        ]);

        $formattedAddress = str_replace(" ", "+", $formattedAddress);

        Log::debug("Efetuando a request para: ", [
            'url' => $URL . "/json?address=" . $formattedAddress . "&key=" . $API_KEY
        ]);

        $response = $http->request('GET', $URL . "/json?address=" . $formattedAddress . "&key=" . $API_KEY);

        if($response && ($response->getStatusCode() == 200)) {

            $data = json_decode($response->getBody()->getContents(), true);

            Log::debug("Resposta do servidor ", [
                'statusCode' => $response->getStatusCode(),
                'message' => $data
            ]);

            return empty($data["results"]) ? $lat_lng_null : $data["results"][0]["geometry"]["location"];

        } else {
            error_log("GeolocationServiceError: Could not get geolocation reason: "
                . $response->getReasonPhrase() . " with result data: " . $response->getBody()->getContents());
            return $lat_lng_null;
        }
    }
}
