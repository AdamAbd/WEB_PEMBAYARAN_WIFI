<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('bills', function (Blueprint $table) {
        $table->string('bulan')->nullable()->after('due_date'); // Format: 'YYYY-MM'
    });
}

public function down()
{
    Schema::table('bills', function (Blueprint $table) {
        $table->dropColumn('bulan');
    });
}


    /**
     * Reverse the migrations.
     */
    
};
