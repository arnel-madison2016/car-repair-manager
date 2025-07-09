<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Service;
use Faker\Factory as Faker;

class ServiceSeeder extends Seeder {
    
    /**
     * Run the database seeds.
     */
    public function run(): void {

        $data = [
            'Entretien courant' => [
                'Vidange moteur',
                'Remplacement filtres',
                'Bougies',
                'Purge liquide de frein',
            ],
            'Pneumatique' => [
                'Montage pneus',
                'Équilibrage',
                'Parallélisme',
                'Réparation crevaison',
            ],
            'Freinage' => [
                'Plaquettes',
                'Disques',
                'Liquide de frein',
            ],
            'Électricité & Batterie' => [
                'Batterie',
                'Alternateur',
                'Éclairage',
            ],
            'Transmission / Embrayage' => [
                'Embrayage',
                'Volant moteur',
                'Cardans',
            ],
            'Climatisation' => [
                'Recharge gaz',
                'Filtre habitacle',
                'Détection fuite',
            ],
            'Suspension & Direction' => [
                'Amortisseurs',
                'Rotules',
                'Géométrie',
            ],
            'Contrôle technique' => [
                'Pré-contrôle',
                'Réparation contre-visite',
            ],
            'Nettoyage & Esthétique' => [
                'Lavage extérieur',
                'Nettoyage intérieur',
                'Polissage phares',
            ],
            'Diagnostic & électronique' => [
                'Diagnostic valise',
                'Effacement défauts',
                'Reprogrammation ECU',
            ],
            'Sécurité & Équipements' => [
                'Alarme',
                'Réglage phares',
            ],
            'Carrosserie / Peinture' => [
                'Débosselage',
                'Peinture',
                'Réparation pare-chocs',
            ],
        ];

        $faker = Faker::create();

        foreach ($data as $category => $services) {
            $cat = Category::where('name', $category)->first();

            foreach ($services as $service) {
                Service::create([
                    'category_service_id' => $cat->id,
                    'name' => $service,
                    'details' => $faker->paragraph(3),
                ]);
            }
        }
    }
}
