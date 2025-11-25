<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;

class PositionSeeder extends Seeder
{
    public function run()
    {
        $positions = [
            [
                'name' => 'Presidente',
                'description' => 'Director ejecutivo de la empresa',
                'is_president' => true
            ],
            [
                'name' => 'Vicepresidente',
                'description' => 'Segundo al mando',
                'is_president' => false
            ],
            [
                'name' => 'Gerente General',
                'description' => 'Gerente de área',
                'is_president' => false
            ],
            [
                'name' => 'Gerente de Recursos Humanos',
                'description' => 'Encargado del área de recursos humanos',
                'is_president' => false
            ],
            [
                'name' => 'Gerente de Ventas',
                'description' => 'Encargado del área de ventas',
                'is_president' => false
            ],
            [
                'name' => 'Gerente de Tecnología',
                'description' => 'Encargado del área de tecnología',
                'is_president' => false
            ],
            [
                'name' => 'Analista de Sistemas',
                'description' => 'Desarrollador de software',
                'is_president' => false
            ],
            [
                'name' => 'Contador',
                'description' => 'Encargado de contabilidad',
                'is_president' => false
            ],
            [
                'name' => 'Asistente Administrativo',
                'description' => 'Apoyo administrativo',
                'is_president' => false
            ],
            [
                'name' => 'Coordinador de Proyectos',
                'description' => 'Coordinación de proyectos',
                'is_president' => false
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
