<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'email' => 'admin@gmail.com',
                'password' => Hash::make('admin123'),
                'nama' => 'Admin',
                'nim' => null,
                'kelas' => null,
                'telp' => '08123456789',
                'prodi' => null,
                'instansi' => 'Admin Institute',
                'status' => 'dosen',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'user@gmail.com',
                'password' => Hash::make('admin123'),
                'nama' => 'Fandi Aziz Pratama',
                'nim' => '200103096',
                'kelas' => 'TI20A4',
                'telp' => '0895392518509',
                'prodi' => 'Teknik Informatika',
                'instansi' => 'Universitas Duta Bangsa Surakarta',
                'status' => 'mahasiswa',
                'role' => 'pendaftar',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
