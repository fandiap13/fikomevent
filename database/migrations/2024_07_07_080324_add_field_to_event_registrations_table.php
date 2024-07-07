<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldToEventRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->string('nama');
            $table->string('telp', 50);
            $table->string('instansi');
            $table->enum('status', ['mahasiswa', 'dosen', 'umum'])->default('mahasiswa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropColumn(['nama', 'telp', 'instansi', 'status']);
        });
    }
}
