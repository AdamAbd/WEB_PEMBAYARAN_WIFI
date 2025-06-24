<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 8, 2); // Jumlah tagihan
            $table->date('due_date'); // Tanggal jatuh tempo
            $table->enum('status', ['pending', 'paid'])->default('pending'); // Status tagihan
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Menambahkan kolom user_id sebagai FK
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
