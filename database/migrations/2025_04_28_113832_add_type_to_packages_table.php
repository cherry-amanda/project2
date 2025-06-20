<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Kosong karena 'type' sudah ada di struktur awal
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            // Kosong juga atau dropColumn('type') jika mau rollback kolom itu
            // $table->dropColumn('type');
        });
    }
};
