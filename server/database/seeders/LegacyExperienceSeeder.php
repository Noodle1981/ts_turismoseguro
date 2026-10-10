<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Experience;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LegacyExperienceSeeder extends Seeder
{
    /**
     * Seed authentic tourist experiences from legacy data.
     */
    public function run(): void
    {
        $calingasta = Department::where('slug', 'calingasta')->first();
        $valleFertil = Department::where('slug', 'valle-fertil')->first();
        $caucete = Department::where('slug', 'caucete')->first();
        $iglesia = Department::where('slug', 'iglesia')->first();
        $ullum = Department::where('slug', 'ullum')->first();

        $experiences = [
            [
                'title' => 'Tour de las Estrellas en El Leoncito',
                'department_id' => $calingasta?->id,
                'category' => 'Astronomía',
                'main_location' => 'Barreal, Calingasta',
                'price_per_person' => 12000,
                'duration' => '3-4 horas (nocturno)',
                'short_description' => 'Una noche mágica bajo uno de los cielos más limpios y diáfanos del planeta.',
                'long_description' => 'Sumérgete en la inmensidad del cosmos desde el Parque Nacional El Leoncito, un sitio privilegiado por su atmósfera diáfana. Esta experiencia incluye charla introductoria sobre astronomía, observación con telescopios profesionales guiada por astrónomos y guías matriculados.',
                'operator_name' => 'AstroTours San Juan',
                'operator_whatsapp' => '+54 9 264 123-4567',
                'operator_email' => 'info@astrotourssj.com',
                'primary_image' => 'storage/img/experiencies/cartelparqueleoncito.jpg',
                'featured' => true,
                'includes' => ['Guía astronómico especializado', 'Uso de telescopios profesionales', 'Charla introductoria', 'Chocolate caliente en invierno'],
                'not_includes' => ['Traslado al Parque Nacional', 'Cena'],
                'recommendations' => ['Llevar abrigo incluso en verano', 'Calzado cómodo', 'No usar linternas de luz blanca'],
            ],
            [
                'title' => 'Excursión a Ischigualasto (Valle de la Luna)',
                'department_id' => $valleFertil?->id,
                'category' => 'Paleontología & Naturaleza',
                'main_location' => 'Parque Provincial Ischigualasto, Valle Fértil',
                'price_per_person' => 20000,
                'duration' => 'Día completo (8-10 horas)',
                'short_description' => 'Viaja millones de años al pasado en un paisaje de otro planeta, Patrimonio de la Humanidad.',
                'long_description' => 'Descubre las geoformas erosionadas por el viento y el agua que dan a Ischigualasto su apariencia lunar. Recorre formaciones icónicas como El Hongo, El Submarino y la Cancha de Bochas. Cuna de los fósiles de dinosaurios más antiguos de la Tierra.',
                'operator_name' => 'Aventuras Triásicas',
                'operator_whatsapp' => '+54 9 264 765-4321',
                'operator_email' => 'contacto@aventurastriasicas.tur.ar',
                'primary_image' => 'storage/img/experiencies/valledelaluna.jpg',
                'featured' => true,
                'includes' => ['Entrada al Parque Provincial', 'Guía oficial del parque', 'Visita al Museo Paleontológico'],
                'not_includes' => ['Almuerzo y bebidas', 'Propinas'],
                'recommendations' => ['Llevar sombrero o gorra', 'Protector solar FPS 50+', 'Agua abundante (mínimo 2 litros)'],
            ],
            [
                'title' => 'Santuario Difunta Correa: Fe y Tradición',
                'department_id' => $caucete?->id,
                'category' => 'Cultural & Religioso',
                'main_location' => 'Vallecito, Caucete',
                'price_per_person' => 8000,
                'duration' => 'Medio día',
                'short_description' => 'Conoce uno de los santuarios populares más multitudinarios y emotivos de Argentina.',
                'long_description' => 'Visita el paraje de Vallecito, epicentro de la devoción popular a Deolinda Correa. Recorre las capillas llenas de ofrendas, testimonios de agradecimiento y leyendas que rodean a esta figura mítica del folclore cuyano.',
                'operator_name' => 'Tradiciones Sanjuaninas Tours',
                'operator_whatsapp' => '+54 9 264 333-2211',
                'operator_email' => 'info@tradicionesanjuan.com.ar',
                'primary_image' => 'storage/img/experiencies/difuntacorrea.jpg',
                'featured' => false,
                'includes' => ['Guía cultural e histórico', 'Tiempo libre para recorrer las capillas y museo'],
                'not_includes' => ['Comidas y compras de artesanías'],
                'recommendations' => ['Respeto por el lugar y los devotos', 'Calzado cómodo'],
            ],
            [
                'title' => 'Adrenalina en Cuesta del Viento: Windsurf & Kite',
                'department_id' => $iglesia?->id,
                'category' => 'Deportes Extremos',
                'main_location' => 'Dique Cuesta del Viento, Rodeo, Iglesia',
                'price_per_person' => 25000,
                'duration' => '3 horas (clase + práctica)',
                'short_description' => 'Desafía el viento térmico en uno de los mejores spots del mundo para navegación a vela.',
                'long_description' => 'Siente la emoción del windsurf o kitesurf en el Dique Cuesta del Viento. Clases personalizadas para principiantes y alquiler de equipos de alta gama para navegantes experimentados bajo la tutela de instructores certificados.',
                'operator_name' => 'Viento Extremo San Juan',
                'operator_whatsapp' => '+54 9 264 999-8877',
                'operator_email' => 'contacto@vientoextremosj.com',
                'primary_image' => 'storage/img/experiencies/cuestadelviento.JPG',
                'featured' => true,
                'includes' => ['Instructor certificado', 'Equipo completo (tabla, vela/kite, chaleco, traje de neoprene)'],
                'not_includes' => ['Transporte hasta el dique'],
                'recommendations' => ['Saber nadar básico', 'Protector solar resistente al agua', 'Toalla y abrigo para post-sesión'],
            ],
            [
                'title' => 'Carrovelismo en la Pampa El Leoncito',
                'department_id' => $calingasta?->id,
                'category' => 'Aventura en Tierra',
                'main_location' => 'Barreal Blanco (Pampa El Leoncito), Calingasta',
                'price_per_person' => 18000,
                'duration' => '1 a 2 horas',
                'short_description' => 'Siente la velocidad impulsado por el viento en la inmensidad del Barreal Blanco.',
                'long_description' => 'Experimenta la sensación de velocidad y libertad sobre la planicie arcillosa de la Pampa El Leoncito en un carro a vela. Actividad segura y apta para toda la familia con la Cordillera de los Andes como telón de fondo.',
                'operator_name' => 'Barreal Carrovelas Aventura',
                'operator_whatsapp' => '+54 9 264 555-4433',
                'operator_email' => 'reservas@barrealcarrovelas.com',
                'primary_image' => 'storage/img/experiencies/carrovelismo.jpg',
                'featured' => false,
                'includes' => ['Instrucción técnica previa', 'Alquiler de carrovela', 'Casco y guantes homologados'],
                'not_includes' => ['Traslado al Barreal Blanco'],
                'recommendations' => ['Ropa cómoda que corte el viento', 'Gafas de sol polarizadas', 'Protector labial y solar'],
            ],
            [
                'title' => 'Relax y Kayak en el Dique de Ullum',
                'department_id' => $ullum?->id,
                'category' => 'Acuática & Relax',
                'main_location' => 'Embarcadero Dique de Ullum',
                'price_per_person' => 15000,
                'duration' => 'Medio día',
                'short_description' => 'Paseo en kayak y stand up paddle en las aguas calmas del oasis de Ullum.',
                'long_description' => 'Navega en kayak por las tranquilas bahías del Dique de Ullum, disfrutando de un paisaje de cerros coloridos y aguas cristalinas. Ideal para relajarse y disfrutar del sol cuyano.',
                'operator_name' => 'Ullum Náutica & Aventuras',
                'operator_whatsapp' => '+54 9 264 666-5544',
                'operator_email' => 'info@ullumnautica.com',
                'primary_image' => 'storage/img/experiencies/dique2.jpg',
                'featured' => false,
                'includes' => ['Alquiler de kayak simple o doble', 'Chaleco salvavidas', 'Remo y charla de seguridad'],
                'not_includes' => ['Alimentos y bebidas'],
                'recommendations' => ['Traje de baño', 'Gorra con sujeción', 'Protector solar'],
            ],
        ];

        foreach ($experiences as $expData) {
            $includes = $expData['includes'];
            $notIncludes = $expData['not_includes'];
            $recommendations = $expData['recommendations'];
            $primaryImage = $expData['primary_image'];
            unset($expData['includes'], $expData['not_includes'], $expData['recommendations'], $expData['primary_image']);

            $slug = Str::slug($expData['title']);

            $experience = Experience::updateOrCreate(
                ['slug' => $slug],
                array_merge($expData, [
                    'is_verified' => true,
                ])
            );

            // Imagen principal
            $experience->images()->updateOrCreate(
                ['image_path' => $primaryImage],
                ['is_primary' => true, 'sort_order' => 1, 'caption' => $experience->title]
            );

            // Inclusiones (true)
            foreach ($includes as $item) {
                $experience->includes()->firstOrCreate(['item' => $item, 'is_included' => true]);
            }

            // No inclusiones (false)
            foreach ($notIncludes as $item) {
                $experience->includes()->firstOrCreate(['item' => $item, 'is_included' => false]);
            }

            // Recomendaciones
            foreach ($recommendations as $rec) {
                $experience->recommendations()->firstOrCreate(['recommendation' => $rec]);
            }
        }
    }
}
