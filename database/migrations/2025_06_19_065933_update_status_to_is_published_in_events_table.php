<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusToIsPublishedInEventsTable extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            // Hapus kolom status lama
            $table->dropColumn('status');

            // Tambah kolom is_published (default: false)
            $table->boolean('is_published')->default(false);
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            // Balikin kolom status kalau rollback
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->nullable();
            $table->dropColumn('is_published');
        });
    }
}
