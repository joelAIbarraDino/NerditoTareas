<?php

namespace Database\Seeders;

use App\Models\SpecialistArea;
use App\Models\TypeHomework;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Ittai Rosas',
            'email' => 'ittai@dinozign.com',
            'password' => '2019601919jJ+'
        ]);

        $user->assignRole('admin');

        $specialistAreas = [
            'Ciencias sociales',
            'Matematicas',
            'Ingenieria',
            'Química',
            'Biología',
            'Programación',
            'Administración',
            'Contabilidad',
            'Literatura y escritura'
        ];

        $typesHomework = [
            'Tesis y tesinas',
            'Invesigaciónes',
            'Ensayos',
            'Practicas y experimentos',
            'Mapas mentales',
            'Exposiciones y presentaciones',
            'Programación',
            'Ejercicios'
        ];

        foreach($specialistAreas as $area){
            SpecialistArea::create(['name' => $area]);
        }

        foreach($typesHomework as $type){
            TypeHomework::create(['name' =>$type]);
        }
    }

}
