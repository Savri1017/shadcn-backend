<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Admin', 'Manager', 'Staff'] as $nama) {
            Jabatan::firstOrCreate(['nama_jabatan' => $nama]);
        }
    }
}