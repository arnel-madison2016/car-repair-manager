<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Taxation;

use Faker\Factory as Faker;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrilleTarifaireSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {



        // Récupérer tous les services existants
        $services = Service::all();

        $faker = Faker::create();

        foreach ($services as $service) {

            Taxation::create([
                'service_id' => $service->id,
                'cout_prestation' => $faker->randomFloat(2, 1, 9999999),
                'reduction' => $faker->randomFloat(2, 0.1, 99.99),
            ]);
        }
    }
}
