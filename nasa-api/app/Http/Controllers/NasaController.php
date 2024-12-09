<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NasaController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function fetchData(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $nasaApiUrl = "https://api.nasa.gov/DONKI/CME";
        $apiKey = env('NASA_API_KEY');

        try {
            $response = Http::timeout(30)->get($nasaApiUrl, [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'api_key' => $apiKey
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (empty($data)) {
                    return response()->json(['message' => 'No hay datos disponibles para este rango de fechas.']);
                }

                $instrumentCounts = [];
                foreach ($data as $event) {
                    if (isset($event['instruments'])) {
                        foreach ($event['instruments'] as $instrument) {
                            $name = $instrument['displayName'] ?? 'Desconocido';
                            $instrumentCounts[$name] = ($instrumentCounts[$name] ?? 0) + 1;
                        }
                    }
                }

                $totalInstruments = array_sum($instrumentCounts);
                $instrumentPercentages = [];
                foreach ($instrumentCounts as $name => $count) {
                    $instrumentPercentages[$name] = round(($count / $totalInstruments) * 100, 2);
                }

                return response()->json([
                    'data' => $data,
                    'instrumentPercentages' => $instrumentPercentages,
                ]);
            } else {
                return response()->json(['error' => 'Hubo un problema al obtener los datos de la NASA'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error en el servidor. Por favor, intenta más tarde.'], 500);
        }
    }
}
