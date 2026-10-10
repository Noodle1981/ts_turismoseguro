<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Seed the 19 departments of San Juan with authentic photos.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Calingasta',
                'description' => 'Tierra de la Cordillera de los Andes, Barreal, astronomía de clase mundial y el cerro Alcázar.',
                'hero_image' => 'storage/img/departamentos/calingasta.jpg',
            ],
            [
                'name' => 'Valle Fértil',
                'description' => 'Cuna de dinosaurios en Ischigualasto (Valle de la Luna), serranías verdes y microclima único.',
                'hero_image' => 'storage/img/departamentos/valle_fertil.jpg',
            ],
            [
                'name' => 'Iglesia',
                'description' => 'Rodeado de termas en Pismanta, el Dique Cuesta del Viento para kitesurf y valles cordilleranos.',
                'hero_image' => 'storage/img/departamentos/iglesia.jpg',
            ],
            [
                'name' => 'Jáchal',
                'description' => 'Tradición gaucha, molinos harineros históricos y rica gastronomía típica cuyana.',
                'hero_image' => 'storage/img/departamentos/jachal.jpg',
            ],
            [
                'name' => 'Zonda',
                'description' => 'Cercano a la Quebrada de Zonda, viñedos boutique, cabañas de descanso y deportes de aventura.',
                'hero_image' => 'storage/img/departamentos/zonda.jpg',
            ],
            [
                'name' => 'Ullum',
                'description' => 'El Dique de Ullum con deportes acuáticos, sol permanente y hermosas vistas al lago.',
                'hero_image' => 'storage/img/departamentos/ullum.jpg',
            ],
            [
                'name' => 'Rawson',
                'description' => 'Zona del Medanito, fincas con piscinas y espacios verdes de recreación familiar.',
                'hero_image' => 'storage/img/departamentos/rawson.jpg',
            ],
            [
                'name' => 'Pocito',
                'description' => 'Ruta del Vino y del Olivo, quintas campestres y gastronomía rural sanjuanina.',
                'hero_image' => 'storage/img/departamentos/pocito.jpg',
            ],
            [
                'name' => 'Capital',
                'description' => 'Centro cívico, Teatro del Bicentenario, Casa Natal de Sarmiento y oferta hotelera urbana.',
                'hero_image' => 'storage/img/departamentos/capital.jpg',
            ],
            [
                'name' => 'Rivadavia',
                'description' => 'El Parque Faunístico, Quebrada de Zonda y accesos a rutas turísticas hacia el oeste.',
                'hero_image' => 'storage/img/departamentos/rivadavia.jpg',
            ],
            [
                'name' => 'Santa Lucía',
                'description' => 'Casonas tradicionales con parques añosos, ambiente campestre y cercanía a la ciudad.',
                'hero_image' => 'storage/img/departamentos/santa_lucia.jpg',
            ],
            [
                'name' => 'Caucete',
                'description' => 'Santuario de la Difunta Correa, historia vitivinícola y bodegas tradicionales.',
                'hero_image' => 'storage/img/departamentos/caucete.jpg',
            ],
            [
                'name' => 'Albardón',
                'description' => 'Aguas termales de La Laja, autódromo Villicum y valles agrícolas.',
                'hero_image' => 'storage/img/departamentos/albardon.jpg',
            ],
            [
                'name' => 'Angaco',
                'description' => 'Caminos rurales, viñedos centenarios y tranquilidad campestre.',
                'hero_image' => 'storage/img/departamentos/angaco.jpg',
            ],
            [
                'name' => 'San Martín',
                'description' => 'Santuario de San Ceferino Namuncurá, bodegas y vistas panorámicas del Pie de Palo.',
                'hero_image' => 'storage/img/departamentos/san_martin.jpg',
            ],
            [
                'name' => 'Sarmiento',
                'description' => 'Valle de Pedernal con vinos de altura excepcionales y lagunas de Huanacache.',
                'hero_image' => 'storage/img/departamentos/sarmiento.JPG',
            ],
            [
                'name' => '9 de Julio',
                'description' => 'Chacras familiares, producción olivícola y serenidad cuyana.',
                'hero_image' => 'storage/img/departamentos/9_de_julio.jpg',
            ],
            [
                'name' => '25 de Mayo',
                'description' => 'Tierra de contrastes, médanos y agricultura bajo riego.',
                'hero_image' => 'storage/img/departamentos/25_de_mayo.jpg',
            ],
            [
                'name' => 'Chimbas',
                'description' => 'Costanera del Río San Juan y celebraciones culturales del carnaval.',
                'hero_image' => 'storage/img/departamentos/chimbas.jpg',
            ],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                ['slug' => Str::slug($dept['name'])],
                [
                    'name' => $dept['name'],
                    'description' => $dept['description'],
                    'hero_image' => $dept['hero_image'],
                    'is_active' => true,
                ]
            );
        }
    }
}
