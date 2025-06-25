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
      
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('metode', ['cash', 'transfer'])->default('transfer');
        });
    
    
    }
        
    

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('metode');
        });
    }

};
