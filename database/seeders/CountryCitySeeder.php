<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country; // Aquí Country = Departamento
use App\Models\City;    // Aquí City = Municipio

class CountryCitySeeder extends Seeder
{
    public function run()
    {

        // Antioquia
        $antioquia = Country::create([
            'name' => 'Antioquia',
            'code' => 'ANT'
        ]);

        $antioquiaMunicipalities = [
            'Medellín',
            'Envigado',
            'Bello',
            'Itagüí',
            'Rionegro',
            'Sabaneta',
        ];

        foreach ($antioquiaMunicipalities as $name) {
            City::create([
                'name' => $name,
                'country_id' => $antioquia->id
            ]);
        }

        // Cundinamarca
        $cundinamarca = Country::create([
            'name' => 'Cundinamarca',
            'code' => 'CUN'
        ]);

        $cundinamarcaMunicipalities = [
            'Bogotá',
            'Soacha',
            'Chía',
            'Zipaquirá',
            'Girardot',
            'Fusagasugá',
        ];

        foreach ($cundinamarcaMunicipalities as $name) {
            City::create([
                'name' => $name,
                'country_id' => $cundinamarca->id
            ]);
        }

        // Valle del Cauca
        $valle = Country::create([
            'name' => 'Valle del Cauca',
            'code' => 'VAC'
        ]);

        $valleMunicipalities = [
            'Cali',
            'Palmira',
            'Buenaventura',
            'Tuluá',
            'Cartago',
            'Buga',
        ];

        foreach ($valleMunicipalities as $name) {
            City::create([
                'name' => $name,
                'country_id' => $valle->id
            ]);
        }

        // Atlántico
        $atlantico = Country::create([
            'name' => 'Atlántico',
            'code' => 'ATL'
        ]);

        $atlanticoMunicipalities = [
            'Barranquilla',
            'Soledad',
            'Malambo',
            'Puerto Colombia',
            'Galapa',
        ];

        foreach ($atlanticoMunicipalities as $name) {
            City::create([
                'name' => $name,
                'country_id' => $atlantico->id
            ]);
        }

        // Bolívar
        $bolivar = Country::create([
            'name' => 'Bolívar',
            'code' => 'BOL'
        ]);

        $bolivarMunicipalities = [
            'Cartagena',
            'Magangué',
            'Turbaco',
            'Arjona',
            'Mompox',
        ];

        foreach ($bolivarMunicipalities as $name) {
            City::create([
                'name' => $name,
                'country_id' => $bolivar->id
            ]);
        }
    }
}
