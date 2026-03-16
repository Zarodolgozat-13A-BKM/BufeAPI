<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class JedlikCsengoService
{
    protected $baseUrl = "";
    public function __construct()
    {
        $this->baseUrl = config('services.jedlikCsengo.baseUrl');
    }
    protected function client()
    {
        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Accept' => 'application/json',
            ])->withoutVerifying();
    }
    public function getRingTableForDate($date = null)
    {
        if (!$date) {
            $date = date('Y-m-d');
        }
        $resp = $this->client()->get('/' . $date);
        $breaks = [];
        if ($resp->successful()) {
            $data = $resp->json();
            for ($i = 1; $i <= 8; $i++) {
                $breaks[] = ['start' => $data[$i]['kicsengetés'], 'end' => $data[$i + 1]['becsengetés']];
            }
            return $breaks;
        }
    }
}
