<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {

        $categories = [
            'Entretien courant',
            'Pneumatique',
            'Freinage',
            'Électricité & Batterie',
            'Transmission / Embrayage',
            'Climatisation',
            'Suspension & Direction',
            'Contrôle technique',
            'Nettoyage & Esthétique',
            'Diagnostic & électronique',
            'Sécurité & Équipements',
            'Carrosserie / Peinture',
        ];

        $faker = Faker::create();

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'cout_forfaitaire' => $faker->randomFloat(2, 1, 9999999),
                'details' => $faker->paragraph(3),
            ]);
        }
    }
}
