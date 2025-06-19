<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CarModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{

        $brands = [
            [
                'name' => 'Toyota', 
                'car_models' => [
                    ['name' => 'Corolla'],
                    ['name' => 'Camry'],
                    ['name' => 'RAV4'],
                    ['name' => 'Yaris'],
                    ['name' => 'Land Cruiser'],
                    ['name' => 'Hilux'],
                    ['name' => 'Prius'],
                ]
            ],
            [
                'name' => 'Lexus', 
                'car_models' => [
                    ['name' => 'RX'],
                    ['name' => 'NX'],
                    ['name' => 'IS'],
                    ['name' => 'ES'],
                    ['name' => 'UX'],
                    ['name' => 'LS'],
                    ['name' => 'GX']
                ]
            ],
            [
                'name' => 'Daihatsu',
                'car_models' => [
                    ['name' => 'Copen'],
                    ['name' => 'Move'],
                    ['name' => 'Hijet'],
                    ['name' => 'Mira'],
                    ['name' => 'Terios'],
                ]
            ],
            [
                'name' => 'Honda',
                'car_models' => [
                    ['name' => 'Civic'],
                    ['name' => 'Accord'],
                    ['name' => 'CR-V'],
                    ['name' => 'HR-V'],
                    ['name' => 'Jazz'],
                    ['name' => 'Fit'],
                ]
            ],
            [
                'name' => 'Acura',
                'car_models' => [
                    ['name' => 'ILX'],
                    ['name' => 'TLX'],
                    ['name' => 'RDX'],
                    ['name' => 'MDX'],
                    ['name' => 'NSX'],
                ]
            ],            
            [
                'name' => 'Nissan',
                'car_models' => [
                    ['name' => 'Altima'],
                    ['name' => 'Sentra'],
                    ['name' => 'Leaf'],
                    ['name' => 'Qashqai'],
                    ['name' => 'Rogue'],
                    ['name' => 'GT-R'],
                ]
            ],
            [
                'name' => 'Infiniti',
                'car_models' => [
                    ['name' => 'Q50'],
                    ['name' => 'Q60'],
                    ['name' => 'QX50'],
                    ['name' => 'QX60'],
                    ['name' => 'QX80'],
                ]   
            ],
            [
                'name' => 'Datsun',
                'car_models' => [
                    ['name' => 'GO'],
                    ['name' => 'GO+'],
                    ['name' => 'Redi-GO'],
                ]
            ],
            [
                'name' => 'Mazda',
                'car_models' => [
                    ['name' => 'Mazda3'],
                    ['name' => 'Mazda6'],
                    ['name' => 'CX-5'],
                    ['name' => 'CX-3'],
                    ['name' => 'MX-5'],
                ]
            ],
            [
                'name' => 'Subaru',
                'car_models' => [
                    ['name' => 'Impreza'],
                    ['name' => 'Forester'],
                    ['name' => 'Outback'],
                    ['name' => 'WRX'],
                    ['name' => 'Ascent'],
                ]
            ],
            [
                'name' => 'Mitsubishi',
                'car_models' => [
                    ['name' => 'Outlander'],
                    ['name' => 'Lancer'],
                    ['name' => 'Pajero'],
                    ['name' => 'ASX'],
                    ['name' => 'Eclipse Cross'],
                ]
            ],
            [
                'name' => 'Suzuki',
                'car_models' => [
                    ['name' => 'Swift'],
                    ['name' => 'Vitara'],
                    ['name' => 'Jimny'],
                    ['name' => 'Baleno'],
                    ['name' => 'S-Cross'],
                ]
            ],
            [
                'name' => 'Volkswagen',
                'car_models' => [
                    ['name' => 'Golf'],
                    ['name' => 'Polo'],
                    ['name' => 'Passat'],
                    ['name' => 'Tiguan'],
                    ['name' => 'ID.4'],
                ]
            ],
            [
                'name' => 'Audi',
                'car_models' => [
                    ['name' => 'A3'],
                    ['name' => 'A4'],
                    ['name' => 'A6'],
                    ['name' => 'Q3'],
                    ['name' => 'Q5'],
                    ['name' => 'Q7'],
                    ['name' => 'e-tron'],
                ]
            ],
            [
                'name' => 'Porsche',
                'car_models' => [
                    ['name' => '911'],
                    ['name' => 'Cayenne'],
                    ['name' => 'Macan'],
                    ['name' => 'Taycan'],
                    ['name' => 'Panamera'],
                ]
            ],
            [
                'name' => 'Seat',
                'car_models' => [
                    ['name' => 'Ibiza'],
                    ['name' => 'Leon'],
                    ['name' => 'Arona'],
                    ['name' => 'Ateca'],
                ]
            ],
            [
                'name' => 'Skoda',
                'car_models' => [
                    ['name' => 'Octavia'],
                    ['name' => 'Superb'],
                    ['name' => 'Fabia'],
                    ['name' => 'Kodiaq'],
                    ['name' => 'Kamiq'],
                ]
            ],
            [
                'name' => 'Bentley',
                'car_models' => [
                    ['name' => 'Bentayga'],
                    ['name' => 'Continental GT'],
                    ['name' => 'Flying Spur'],
                ]
            ],
            [
                'name' => 'Bugatti',
                'car_models' => [
                    ['name' => 'Chiron'],
                    ['name' => 'Veyron'],
                    ['name' => 'Divo'],
                ]
            ],
            [
                'name' => 'Lamborghini',
                'car_models' => [
                    ['name' => 'Huracan'],
                    ['name' => 'Aventador'],
                    ['name' => 'Urus']
                ]
            ],
            [
                'name' => 'BMW',
                'car_models' => [
                    ['name' => '3 Series'],
                    ['name' => '5 Series'],
                    ['name' => 'X1'],
                    ['name' => 'X5'],
                    ['name' => 'i4'],
                    ['name' => 'iX'],
                ]
            ],
            [
                'name' => 'Mini',
                'car_models' => [
                    ['name' => 'Cooper'],
                    ['name' => 'Countryman'],
                    ['name' => 'Clubman'],
                ]
            ],
            [
                'name' => 'Rolls-Royce',
                'car_models' => [
                    ['name' => 'Phantom'],
                    ['name' => 'Ghost'],
                    ['name' => 'Cullinan'],
                    ['name' => 'Wraith'],
                ]
            ],
            [
                'name' => 'Mercedes Benz',
                'car_models' => [
                    ['name' => 'A-Class'],
                    ['name' => 'C-Class'],
                    ['name' => 'E-Class'],
                    ['name' => 'GLA'],
                    ['name' => 'GLE'],
                    ['name' => 'S-Class'],
                ]
            ],
            [
                'name' => 'Maybach',
                'car_models' => [
                    ['name' => 'S 680'],
                    ['name' => 'GLS 600'],
                ]
            ],
            [
                'name' => 'Smart',
                'car_models' => [
                    ['name' => 'ForTwo'],
                    ['name' => 'ForFour'],
                    ['name' => 'Smart EQ'],
                ]
            ],
            [
                'name' => 'Chevrolet',
                'car_models' => [
                    ['name' => 'Spark'],
                    ['name' => 'Malibu'],
                    ['name' => 'Equinox'],
                    ['name' => 'Camaro'],
                    ['name' => 'Silverado'],
                ]
            ],
            [
                'name' => 'GMC',
                'car_models' => [
                    ['name' => 'Terrain'],
                    ['name' => 'Acadia'],
                    ['name' => 'Sierra'],
                    ['name' => 'Yukon'],
                ]
            ],
            [
                'name' => 'Cadillac',
                'car_models' => [
                    ['name' => 'Escalade'],
                    ['name' => 'CT4'],
                    ['name' => 'CT5'],
                    ['name' => 'XT5'],
                ]
            ],
            [
                'name' => 'Buick',
                'car_models' => [
                    ['name' => 'Encore'],
                    ['name' => 'Enclave'],
                    ['name' => 'LaCrosse'],
                ]
            ],
            [
                'name' => 'Hummer',
                'car_models' => [
                    ['name' => 'H1'],
                    ['name' => 'H2'],
                    ['name' => 'H3'],
                    ['name' => 'Hummer EV'],
                ]
            ],
            [
                'name' => 'Ford',
                'car_models' => [
                    ['name' => 'Focus'],
                    ['name' => 'Fiesta'],
                    ['name' => 'Mustang'],
                    ['name' => 'Explorer'],
                    ['name' => 'F-150'],
                ]
            ],
            [
                'name' => 'Lincoln',
                'car_models' => [
                    ['name' => 'Corsair'],
                    ['name' => 'Nautilus'],
                    ['name' => 'Aviator'],
                    ['name' => 'Navigator'],
                ]
            ],
            [
                'name' => 'Chrysler',
                'car_models' => [
                    ['name' => '300'],
                    ['name' => 'Pacifica'],
                    ['name' => 'Voyager'],
                ]
            ],
            [
                'name' => 'Dodge',
                'car_models' => [
                    ['name' => 'Charger'],
                    ['name' => 'Challenger'],
                    ['name' => 'Durango'],
                ]
            ],
            [
                'name' => 'Jeep',
                'car_models' => [
                    ['name' => 'Wrangler'],
                    ['name' => 'Cherokee'],
                    ['name' => 'Grand Cherokee'],
                    ['name' => 'Compass'],
                ]
            ],
            [
                'name' => 'Ram',
                'car_models' => [
                    ['name' => '1500'],
                    ['name' => '2500'],
                    ['name' => 'ProMaster'],
                ]
            ],
            [
                'name' => 'Tesla',
                'car_models' => [
                    ['name' => 'Model S'],
                    ['name' => 'Model 3'],
                    ['name' => 'Model X'],
                    ['name' => 'Model Y'],
                    ['name' => 'Cybertruck'],
                ]
            ],
            [
                'name' => 'Renault',
                'car_models' => [
                    ['name' => 'Clio'],
                    ['name' => 'Megane'],
                    ['name' => 'Captur'],
                    ['name' => 'Scenic'],
                    ['name' => 'ZOE'],
                ]
            ],
            [
                'name' => 'Dacia',
                'car_models' => [
                    ['name' => 'Sandero'],
                    ['name' => 'Duster'],
                    ['name' => 'Jogger'],
                    ['name' => 'Spring'],
                ]
            ],
            [
                'name' => 'Alpine',
                'car_models' => [
                    ['name' => 'A110'],
                ]
            ],
            [
                'name' => 'Peugeot',
                'car_models' => [
                    ['name' => '208'],
                    ['name' => '308'],
                    ['name' => '3008'],
                    ['name' => '5008'],
                    ['name' => 'e-208'],
                ]
            ],
            [
                'name' => 'Citroën',
                'car_models' => [
                    ['name' => 'C3'],
                    ['name' => 'C4'],
                    ['name' => 'C5 Aircross'],
                    ['name' => 'Berlingo'],
                ]
            ],
            [
                'name' => 'DS Automobiles',
                'car_models' => [
                    ['name' => 'DS 3'],
                    ['name' => 'DS 4'],
                    ['name' => 'DS 7'],
                    ['name' => 'DS 9'],
                ]
            ],
            [
                'name' => 'Opel',
                'car_models' => [
                    ['name' => 'Corsa'],
                    ['name' => 'Astra'],
                    ['name' => 'Mokka'],
                    ['name' => 'Insignia'],
                ]
            ],    
            [
                'name' => 'Fiat',
                'car_models' => [
                    ['name' => '500'],
                    ['name' => 'Panda'],
                    ['name' => 'Tipo'],
                    ['name' => '500X'],
                ]
            ],
            [
                'name' => 'Alfa Romeo',
                'car_models' => [
                    ['name' => 'Giulia'],
                    ['name' => 'Stelvio'],
                    ['name' => 'Tonale'],
                ]
            ],
            [
                'name' => 'Lancia',
                'car_models' => [
                    ['name' => 'Ypsilon'],
                ]
            ],
            [
                'name' => 'Maserati',
                'car_models' => [
                    ['name' => 'Ghibli'],
                    ['name' => 'Levante'],
                    ['name' => 'Quattroporte'],
                    ['name' => 'Grecale'],
                ]
            ],
            [
                'name' => 'Ferrari',
                'car_models' => [
                    ['name' => 'F8'],
                    ['name' => '812'],
                    ['name' => 'SF90'],
                    ['name' => 'Roma'],
                    ['name' => 'Purosangue'],
                ]
            ],
            [
                'name' => 'Jaguar',
                'car_models' => [
                    ['name' => 'XE'],
                    ['name' => 'XF'],
                    ['name' => 'F-Pace'],
                    ['name' => 'I-Pace'],
                    ['name' => 'F-Type'],
                ]
            ],
            [
                'name' => 'Land Rover',
                'car_models' => [
                    ['name' => 'Defender'],
                    ['name' => 'Discovery'],
                    ['name' => 'Range Rover'],
                ]
            ],
            [
                'name' => 'Aston Martin',
                'car_models' => [
                    ['name' => 'Vantage'],
                    ['name' => 'DB11'],
                    ['name' => 'DBX'],
                ]
            ],
            [
                'name' => 'McLaren',
                'car_models' => [
                        ['name' => 'Artura'],
                        ['name' => '720S'],
                        ['name' => 'GT'],
                        ['name' => '765LT'],
                ]
            ],
            [
                'name' => 'Lotus',
                'car_models' => [
                    ['name' => 'Elise'],
                    ['name' => 'Exige'],
                    ['name' => 'Evija'],
                    ['name' => 'Emira'],
                ]
            ],
            [
                'name' => 'Hyundai',
                'car_models' => [
                    ['name' => 'i10'],
                    ['name' => 'i20'],
                    ['name' => 'i30'],
                    ['name' => 'Tucson'],
                    ['name' => 'Kona'],
                    ['name' => 'Santa Fe'],
                ]
            ],
            [
                'name' => 'Kia',
                'car_models' => [
                    ['name' => 'Picanto'],
                    ['name' => 'Rio'],
                    ['name' => 'Ceed'],
                    ['name' => 'Sportage'],
                    ['name' => 'EV6'],
                ]
            ],
            [
                'name' => 'Genesis',
                'car_models' => [
                    ['name' => 'G70'],
                    ['name' => 'G80'],
                    ['name' => 'GV70'],
                    ['name' => 'GV80'],
                ]
            ],
            [
                'name' => 'Geely',
                'car_models' => [
                    ['name' => 'Coolray'],
                    ['name' => 'Emgrand'],
                    ['name' => 'Tugella'],
                ]
            ],
            [
                'name' => 'Volvo',
                'car_models' => [
                    ['name' => 'XC40'],
                    ['name' => 'XC60'],
                    ['name' => 'XC90'],
                    ['name' => 'S60'],
                    ['name' => 'V60'],
                ]
            ],
            [
                'name' => 'Polestar',
                'car_models' => [
                    ['name' => 'Polestar 1'],
                    ['name' => 'Polestar 2'],
                    ['name' => 'Polestar 3'],
                ]
            ],
            [
                'name' => 'BYD',
                'car_models' => [
                    ['name' => 'Han'],
                    ['name' => 'Tang'],
                    ['name' => 'Dolphin'],
                    ['name' => 'Atto 3'],
                    ['name' => 'Seal'],
                ]
            ],
            [
                'name' => 'MG',
                'car_models' => [
                    ['name' => 'ZS EV'],
                    ['name' => 'HS'],
                    ['name' => 'MG4'],
                    ['name' => 'MG5'],
                ]
            ],
            [
                'name' => 'Roewe',
                'car_models' => [
                    ['name' => 'i5'],
                    ['name' => 'RX5'],
                    ['name' => 'Ei5'],
                ]
            ]
        ];

        foreach ($brands as $data) {
           
            // found brand id
            $brand = Brand::where('name', $data['name'])->firstOrFail();

            foreach ($data['car_models'] as $marque) {

                $brand->car_models()->create([
                    'name' => $marque['name'],
                    'brand_id' => $brand->id,
                ]);
            }
        }
    }
}
