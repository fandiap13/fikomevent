<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('nama');
            $table->char('nim', 9)->nullable();
            $table->string('kelas', 100)->nullable();
            $table->string('telp', 50);
            $table->string('prodi')->nullable();
            $table->string('instansi');
            $table->enum('status', ['mahasiswa', 'dosen', 'umum'])->default('mahasiswa');
            $table->enum('role', ['admin', 'pendaftar'])->default('pendaftar');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
