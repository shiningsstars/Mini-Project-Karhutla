<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            [
                'name' => 'Kabupaten Katingan',
                'description' => 'Wilayah pemantauan Karhutla di Kabupaten Katingan, Kalimantan Tengah.',
            ],
            [
                'name' => 'Kabupaten Kapuas',
                'description' => 'Wilayah pemantauan Karhutla di Kabupaten Kapuas, Kalimantan Tengah.',
            ],
            [
                'name' => 'Kota Palangka Raya',
                'description' => 'Wilayah pemantauan Karhutla di Kota Palangka Raya, Kalimantan Tengah.',
            ],
            [
                'name' => 'Kabupaten Kotawaringin Timur',
                'description' => 'Wilayah pemantauan Karhutla di Kabupaten Kotawaringin Timur, Kalimantan Tengah.',
            ],
            [
                'name' => 'Kabupaten Pulang Pisau',
                'description' => 'Wilayah pemantauan Karhutla di Kabupaten Pulang Pisau, Kalimantan Tengah.',
            ],
        ];

        foreach ($areas as $area) {
            Area::updateOrCreate(
                ['name' => $area['name']],
                ['description' => $area['description']]
            );
        }
    }
}
