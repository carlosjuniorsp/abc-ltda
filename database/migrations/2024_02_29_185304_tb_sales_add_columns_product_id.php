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
            $table->foreignId('tb_product_id')->after('tb_client_id')->constrained('tb_products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tb_sales', function (Blueprint $table) {
            $table->dropColumn('tb_product_id');
        });
    }
};
