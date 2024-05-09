<?php

namespace App\Jobs;

use App\Models\Endereco;
use App\Services\GeolocationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RegisterAddressGeoData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var Endereco  */
    private Endereco $endereco;
    /**
     * Create a new job instance.
     */
    public function __construct(Endereco $endereco)
    {
        $this->endereco = $endereco;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (env("APP_ENV") !== "local") {
            $geolocation = [
                'lat' => -30.02214579,
                'lng' => -51.1945232
            ];

        } else {
            $geolocation = GeolocationService::getLatLngByAddress(
                $this->endereco->logradouro,
                $this->endereco->numero,
                $this->endereco->bairro,
                $this->endereco->cidade,
                $this->endereco->cidade->estado->nome
            );
        }

        Log::debug("Geolocation data:", $geolocation);

        $this->endereco->latitude = $geolocation['lat'];
        $this->endereco->longitude = $geolocation['lng'];
        $this->endereco->save();
    }
}
