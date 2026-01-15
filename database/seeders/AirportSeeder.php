<?php

namespace Database\Seeders;

use App\Models\Airport;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = database_path('data/airports.dat');

        if (! file_exists($path)) {
            $this->command->error("File not found: $path");
            return;
        }

        $this->command->info("Reading airport data from $path...");
        $content = file_get_contents($path);

        // Get Countries Map
        // Map English names to IDs.
        $countries = Country::pluck('id', 'name')->mapWithKeys(function ($id, $name) {
            return [strtolower($name) => $id];
        })->all();

        // Also map 'United States' to 'United States of America' if needed, etc.
        // Common discrepancies:
        // OpenFlights: "United States" -> DB: "United States" (usually)

        $this->command->info('Parsing and inserting airports...');

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        DB::table('airports')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $lines = explode("\n", $content);
        $batch = [];
        $batchSize = 500;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            $data = str_getcsv($line);

            if (count($data) < 12) {
                continue;
            }

            // Format: ID, Name, City, Country, IATA, ICAO, Lat, Lon, Alt, Timezone, DST, Tz, Type, Source
            // Indexes:
            // 0: ID
            // 1: Name
            // 2: City
            // 3: Country
            // 4: IATA
            // 5: ICAO
            // 6: Lat
            // 7: Lon
            // 8: Alt

            $name = $data[1] ?? '';
            $city = $data[2] ?? '';
            $countryName = $data[3] ?? '';
            $iata = $data[4] ?? '';
            $icao = $data[5] ?? '';
            $lat = $data[6] ?? null;
            $lon = $data[7] ?? null;
            $alt = $data[8] ?? null;
            $timezone = $data[9] ?? null;
            $dst = $data[10] ?? null;
            $tz = $data[11] ?? null;
            $type = $data[12] ?? null;
            $source = $data[13] ?? null;

            // Handle \N
            if ($iata === '\N') {
                $iata = null;
            }
            if ($icao === '\N') {
                $icao = null;
            }

            // Only insert generic airports or legitimate ones.
            // Type "airport" usually.
            if ($type && $type !== 'airport' && $type !== '\N') {
                // OpenFlights has types like "station", "port", "unknown".
                // We mainly want "airport".
                // But let's verify if we should filter.
                // The snippet showed "airport" for type.
            }

            $countryId = $countries[strtolower($countryName)] ?? null;

            // Try explicit mapping for common mismatches if needed
            if (! $countryId && $countryName == 'United States') {
                $countryId = $countries['united states of america'] ?? null; // Example
            }

            $batch[] = [
                'name' => $name,
                'name_ar' => null,
                'city' => $city,
                'country' => $countryName,
                'country_id' => $countryId,
                'iata' => $iata,
                'icao' => $icao,
                'latitude' => is_numeric($lat) ? $lat : null,
                'longitude' => is_numeric($lon) ? $lon : null,
                'altitude' => is_numeric($alt) ? $alt : null,
                'timezone' => $timezone,
                'dst' => $dst,
                'tz' => $tz,
                'type' => $type,
                'source' => $source,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                Airport::insert($batch);
                $batch = [];
            }
        }

        if (! empty($batch)) {
            Airport::insert($batch);
        }

        $this->command->info('Airports seeded successfully!');
    }
}
