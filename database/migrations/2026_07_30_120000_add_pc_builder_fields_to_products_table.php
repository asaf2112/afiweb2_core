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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'socket')) {
                $table->string('socket')->nullable()->index();
            }
            if (!Schema::hasColumn('products', 'ram_type')) {
                $table->string('ram_type')->nullable()->index();
            }
            if (!Schema::hasColumn('products', 'tdp_watt')) {
                $table->integer('tdp_watt')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'socket')) {
                $table->dropColumn('socket');
            }
            if (Schema::hasColumn('products', 'ram_type')) {
                $table->dropColumn('ram_type');
            }
            if (Schema::hasColumn('products', 'tdp_watt')) {
                $table->dropColumn('tdp_watt');
            }
        });
    }
};
