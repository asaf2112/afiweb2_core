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
        if (!Schema::hasTable('service_requests')) {
            Schema::create('service_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name');
                $table->string('phone');
                $table->string('email')->nullable();
                $table->string('service_type')->nullable();
                $table->string('device_model')->nullable();
                $table->text('issue_description')->nullable();
                $table->text('description')->nullable();
                $table->string('status')->default('pending');
                $table->text('admin_note')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('service_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('service_requests', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                }
                if (!Schema::hasColumn('service_requests', 'email')) {
                    $table->string('email')->nullable();
                }
                if (!Schema::hasColumn('service_requests', 'service_type')) {
                    $table->string('service_type')->nullable();
                }
                if (!Schema::hasColumn('service_requests', 'device_model')) {
                    $table->string('device_model')->nullable();
                }
                if (!Schema::hasColumn('service_requests', 'issue_description')) {
                    $table->text('issue_description')->nullable();
                }
                if (!Schema::hasColumn('service_requests', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('service_requests', 'admin_note')) {
                    $table->text('admin_note')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
