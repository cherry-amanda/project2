<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('package_rabs', function (Blueprint $table) {
            $table->string('nama_manual')->nullable()->after('vendor_service_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_rabs', function (Blueprint $table) {
            //
        });
    }
};
