<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Airport;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\Artisan;

class DebugController extends Controller
{
    public function index()
    {
        $data = [
            'countries_count' => Country::count(),
            'cities_count' => City::count(),
            'airports_count' => Airport::count(),
            'saudi_arabia' => Country::where('code', 'SA')->withCount('cities')->first(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data,
            'message' => 'Seeding status check'
        ]);
    }

    public function runSeeder($class)
    {
        // Security: strictly limit which seeders can be run
        $allowed = ['CitySeeder', 'AirportSeeder', 'CountrySeeder'];
        
        if (!in_array($class, $allowed)) {
            return response()->json(['error' => 'Unauthorized seeder'], 403);
        }

        try {
            Artisan::call('db:seed', ['--class' => $class]);
            return response()->json([
                'status' => 'success',
                'output' => Artisan::output(),
                'message' => "$class executed successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
