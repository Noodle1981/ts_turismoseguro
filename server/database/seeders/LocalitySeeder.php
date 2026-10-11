<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Locality;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDO;

class LocalitySeeder extends Seeder
{
    /**
     * Extrae y puebla las 266 localidades reales de San Juan desde establecimientos.sqlite
     */
    public function run(): void
    {
        $sqlitePath = database_path('establecimientos.sqlite');

        if (!file_exists($sqlitePath)) {
            $this->command?->warn("El archivo {$sqlitePath} no existe. Se omite LocalitySeeder.");
            return;
        }

        $pdo = new PDO("sqlite:{$sqlitePath}");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Mapeo canónico de nombres en establecimientos.sqlite a slugs de Department en Laravel
        $deptMap = [
            '25 DE MAYO' => '25-de-mayo',
            '9 DE JULIO' => '9-de-julio',
            'ALBARDON' => 'albardon',
            'ANGACO' => 'angaco',
            'CALINGASTA' => 'calingasta',
            'CAPITAL' => 'capital',
            'CAUCETE' => 'caucete',
            'CHIMBAS' => 'chimbas',
            'IGLESIA' => 'iglesia',
            'JACHAL' => 'jachal',
            'POCITO' => 'pocito',
            'RAWSON' => 'rawson',
            'RIVADAVIA' => 'rivadavia',
            'SAN MARTIN' => 'san-martin',
            'SANTA LUCIA' => 'santa-lucia',
            'SARMIENTO' => 'sarmiento',
            'ULLUM' => 'ullum',
            'VALLE FERTIL' => 'valle-fertil',
            'ZONDA' => 'zonda',
        ];

        // Obtenemos los departamentos de Laravel
        $departments = Department::all()->keyBy('slug');

        // Consultamos pares unicos con centroides (promedio de lat/lng válidos en San Juan)
        $query = "
            SELECT 
                zona_departamento,
                TRIM(localidad) as localidad_nombre,
                AVG(CASE WHEN latitud BETWEEN -33.0 AND -28.0 THEN latitud ELSE NULL END) as avg_lat,
                AVG(CASE WHEN longitud BETWEEN -71.0 AND -66.0 THEN longitud ELSE NULL END) as avg_lng,
                MIN(codigo_postal) as postal_code
            FROM edificios
            WHERE localidad IS NOT NULL AND TRIM(localidad) != ''
            GROUP BY zona_departamento, TRIM(localidad)
            ORDER BY zona_departamento, TRIM(localidad)
        ";

        $rows = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
        $insertedCount = 0;

        foreach ($rows as $row) {
            $deptKey = trim((string)$row['zona_departamento']);
            $deptSlug = $deptMap[$deptKey] ?? Str::slug($deptKey);

            $department = $departments->get($deptSlug);
            if (!$department) {
                continue;
            }

            $rawName = trim((string)$row['localidad_nombre']);
            // Limpieza y formato adecuado con mayúsculas iniciales
            $cleanName = mb_convert_case(mb_strtolower($rawName, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            $slug = Str::slug($cleanName);

            if (empty($slug)) {
                $slug = 'loc-' . Str::random(6);
            }

            $lat = $row['avg_lat'] ? round((float)$row['avg_lat'], 7) : null;
            $lng = $row['avg_lng'] ? round((float)$row['avg_lng'], 7) : null;
            $cp = !empty($row['postal_code']) && is_numeric($row['postal_code']) ? (int)$row['postal_code'] : null;

            Locality::updateOrCreate(
                [
                    'department_id' => $department->id,
                    'slug' => $slug,
                ],
                [
                    'name' => $cleanName,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'postal_code' => $cp,
                    'is_active' => true,
                ]
            );

            $insertedCount++;
        }

        $this->command?->info("LocalitySeeder: {$insertedCount} localidades procesadas y vinculadas exitosamente.");
    }
}
