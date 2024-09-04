<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // Llama al UserSeeder para agregar un usuario a la base de datos
        $this->call(UserSeeder::class);

        // Si deseas agregar más seeders en el futuro, puedes llamarlos aquí
        // $this->call(OtherSeeder::class);
    }
}
