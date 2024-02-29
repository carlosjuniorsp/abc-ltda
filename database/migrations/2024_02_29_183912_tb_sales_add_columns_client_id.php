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
        Schema::table('tb_sales', function (Blueprint $table) {
            $table->foreignId('tb_client_id')->after('id')->constrained('tb_client');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tb_sales', function (Blueprint $table) {
           $table->dropColumn('tb_client_id');
        });
    }
};
