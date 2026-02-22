<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\SpecialistArea;
use App\Models\TypeHomework;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $specialistAreas = [
            'Ciencias sociales',
            'Matemáticas',
            'Ingeniería',
            'Química',
            'Biología',
            'Programación',
            'Administración',
            'Contabilidad',
            'Literatura y escritura'
        ];

        $typesHomework = [
            'Tesis y tesinas',
            'Investigaciones',
            'Ensayos',
            'Prácticas y experimentos',
            'Mapas mentales',
            'Exposiciones y presentaciones',
            'Programación',
            'Ejercicios'
        ];

        $roles = [
            'admin',
            'specialist',
            'client'
        ];

        foreach($roles as $role){
            Role::create(['name' => $role]);
        }
        
        $user = User::factory()->create([
            'name' => 'Ittai Rosas',
            'email' => 'ittai@dinozign.com',
            'password' => '2019601919jJ+'
        ]);

        $user->assignRole('admin');

        foreach($specialistAreas as $area){
            SpecialistArea::create(['name' => $area]);
        }

        foreach($typesHomework as $type){
            TypeHomework::create(['name' =>$type]);
        }
    }

}
