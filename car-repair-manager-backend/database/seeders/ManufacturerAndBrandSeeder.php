<?php

namespace Database\Seeders;

use App\Models\Manufacturer;
use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManufacturerAndBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {

        $constructeurs = [
            [
                'name' => 'Toyota',
                'country' => 'Japon',
                'brands' => [
                    ['name' => 'Toyota', 'url_picture' => 'toyota.png'],
                    ['name' => 'Lexus', 'url_picture' => 'lexus.png'],
                    ['name' => 'Daihatsu', 'url_picture' => 'daihatsu.png'],
                ]
            ],
            [
                'name' => 'Honda',
                'country' => 'Japon',
                'brands' => [
                    ['name' => 'Honda', 'url_picture' => 'honda.png'],
                    ['name' => 'Acura', 'url_picture' => 'acura.png'],
                ]
            ],
            [
                'name' => 'Nissan',
                'country' => 'Japon',
                'brands' => [
                    ['name' => 'Nissan', 'url_picture' => 'nissan.png'],
                    ['name' => 'Infiniti', 'url_picture' => 'infiniti.png'],
                    ['name' => 'Datsun', 'url_picture' => 'datsun.png'],
                ]
            ],
            [
                'name' => 'Mazda',
                'country' => 'Japon',
                'brands' => [
                    ['name' => 'Mazda', 'url_picture' => 'mazda.png'],
                ]
            ],
            [
                'name' => 'Subaru',
                'country' => 'Japon',
                'brands' => [
                    ['name' => 'Subaru', 'url_picture' => 'subaru.png'],
                ]
            ],
            [
                'name' => 'Mitsubishi',
                'country' => 'Japon',
                'brands' => [
                    ['name' => 'Mitsubishi', 'url_picture' => 'mitsubishi.png'],
                ]
            ],
            [
                'name' => 'Suziki',
                'country' => 'Japon',
                'brands' => [
                    ['name' => 'Suziki', 'url_picture' => 'suziki.png'],
                ]
            ],
            [
                'name' => 'Volkswagen Group',
                'country' => 'Allemagne',
                'brands' => [
                    ['name' => 'Volkswagen', 'url_picture' => 'volkswagen.png'],
                    ['name' => 'Audi', 'url_picture' => 'audi.png'],
                    ['name' => 'Porsche', 'url_picture' => 'porsche.png'],
                    ['name' => 'Seat', 'url_picture' => 'seat.png'],
                    ['name' => 'Skoda', 'url_picture' => 'skoda.png'],
                    ['name' => 'Bentley', 'url_picture' => 'bentley.png'],
                    ['name' => 'Bugatti', 'url_picture' => 'bugatti.png'],
                    ['name' => 'Lamborghini', 'url_picture' => 'lamborghini.png'],
                ]
            ],
            [
                'name' => 'BMW Group',
                'country' => 'Allemagne',
                'brands' => [
                    ['name' => 'Bmw', 'url_picture' => 'bmw.png'],
                    ['name' => 'mini', 'url_picture' => 'mini.png'],
                    ['name' => 'Rolls-Royce', 'url_picture' => 'rolls-royce.png'],
                ]
            ],
            [
                'name' => 'Daimler AG',
                'country' => 'Allemagne',
                'brands' => [
                    ['name' => 'Mercedes Benz', 'url_picture' => 'mercedes-benz.png'],
                    ['name' => 'Maybach', 'url_picture' => 'maybach.png'],
                    ['name' => 'Smart', 'url_picture' => 'smart.png'],
                ]
            ],
            [
                'name' => 'General Motors',
                'country' => 'Etats-Unis',
                'brands' => [
                    ['name' => 'Chevrolet', 'url_picture' => 'chevrolet.png'],
                    ['name' => 'Gmc', 'url_picture' => 'gmc.png'],
                    ['name' => 'Cadillac', 'url_picture' => 'cadillac.png'],
                    ['name' => 'Buick', 'url_picture' => 'buick'],
                    ['name' => 'Hummer', 'url_picture' => 'hummer.png'],
                ]
            ],
            [
                'name' => 'Ford Motor Company',
                'country' => 'Etats-Unis',
                'brands' => [
                    ['name' => 'Ford', 'url_picture' => 'ford.png'],
                    ['name' => 'Lincoln', 'url_picture' => 'lincoln.png'],
                ]
            ],
            [
                'name' => 'Stellantis',
                'country' => 'Etats-Unis',
                'brands' => [
                    ['name' => 'Chrysler', 'url_picture' => 'chrysler.png'],
                    ['name' => 'Dodge', 'url_picture' => 'dodge.png'],
                    ['name' => 'Jeep', 'url_picture' => 'jeep.png'],
                    ['name' => 'Ram', 'url_picture' => 'ram.png'],
                ]
            ],
            [
                'name' => 'Tesla',
                'country' => 'Etats-Unis',
                'brands' => [
                    ['name' => 'Tesla', 'url_picture' => 'tesla.png'],
                ]
            ],
            [
                'name' => 'Renault Group',
                'country' => 'France',
                'brands' => [
                    ['name' => 'Renault', 'url_picture' => 'renault.png'],
                    ['name' => 'Dacia', 'url_picture' => 'dacia.png'],
                    ['name' => 'Alpine', 'url_picture' => 'alpine.png'],
                ]
            ],
            [
                'name' => 'PSA Group',
                'country' => 'France',
                'brands' => [
                    ['name' => 'Peugeot', 'url_picture' => 'peugeot.png'],
                    ['name' => 'Citroën', 'url_picture' => 'citroën.png'],
                    ['name' => 'DS Automobiles', 'url_picture' => 'ds-automobiles.png'],
                    ['name' => 'Opel', 'url_picture' => 'opel.png'],
                ]
            ],
            [
                'name' => 'Fiat Chrysler Automobiles',
                'country' => 'Italie',
                'brands' => [
                    ['name' => 'Fiat', 'url_picture' => 'fiat.png'],
                    ['name' => 'Alfa romeo', 'url_picture' => 'alfa-romeo.png'],
                    ['name' => 'Lancia', 'url_picture' => 'lancia.png'],
                    ['name' => 'Maserati', 'url_picture' => 'maserati.png'],
                    ['name' => 'Ferrari', 'url_picture' => 'ferrari.png'],
                ]
            ],
            [
                'name' => 'Jaguar Land Rover',
                'country' => 'Grande Bretagne',
                'brands' => [
                    ['name' => 'Jaguar', 'url_picture' => 'jaguar.png'],
                    ['name' => 'land Rover', 'url_picture' => 'land-rover.png'],
                ]
            ],
            [
                'name' => 'Autres marques britanniques',
                'country' => 'Grande Bretagne',
                'brands' => [
                    ['name' => 'Aston Martin', 'url_picture' => 'aston-martin.png'],
                    ['name' => 'McLaren', 'url_picture' => 'mclaren.png'],
                    ['name' => 'Lotus', 'url_picture' => 'lotus.png'],
                ]
            ],
            [
                'name' => 'Hyundai Motor group',
                'country' => 'Coree du Sud',
                'brands' => [
                    ['name' => 'Hyundai', 'url_picture' => 'hyundai.png'],
                    ['name' => 'Kia', 'url_picture' => 'kia.png'],
                    ['name' => 'Genesis', 'url_picture' => 'genesis.png'],
                ]
            ],
            [
                'name' => 'Geely',
                'country' => 'chine',
                'brands' => [
                    ['name' => 'Geely', 'url_picture' => 'geely.png'],
                    ['name' => 'Volvo', 'url_picture' => 'volvo.png'],
                    ['name' => 'polestar', 'url_picture' => 'polestar.png'],
                ]
            ],
            [
                'name' => 'BYD',
                'country' => 'chine',
                'brands' => [
                    ['name' => 'Byd', 'url_picture' => 'byd.png'],
                ]
            ],
            [
                'name' => 'SAIC Motor',
                'country' => 'chine',
                'brands' => [
                    ['name' => 'mg', 'url_picture' => 'mg.png'],
                    ['name' => 'Roewe', 'url_picture' => 'roewe.png'],
                ]
            ],
        ];

        // S'assurer que le dossier public/brands existe
        // Storage::disk('public')->makeDirectory('images/brands');

        foreach ($constructeurs as $data) {
            $manufacturer = Manufacturer::create([
                'name' => $data['name'],
                'country' => $data['country']
            ]);

            foreach ($data['brands'] as $brand) {
                // generating unique name
                $name = rand(100, 9999) . time() . '_' .$brand['name'];

                $filename = Str::slug($name) . '.' . pathinfo($brand['url_picture'], PATHINFO_EXTENSION);
                $sourcePath = database_path('seeders/images/brands/' . $brand['url_picture']);
                $destinationPath = 'images/brands/' . $filename;

                if (file_exists($sourcePath)) {
                    Storage::disk('public')->put($destinationPath, file_get_contents($sourcePath));
                }

                $manufacturer->brands()->create([
                    'name' => $brand['name'],
                    'manufacturer_id' => $manufacturer->id,
                    'url_picture' => $destinationPath,
                ]);
            }
        }
    }
}
