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
        // 1. Add soft deletes & indexes to existing tables
        Schema::table('kamars', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('penghunis', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('tanggal_keluar');
        });

        Schema::table('pemasukans', function (Blueprint $table) {
            $table->softDeletes();
            $table->index(['tanggal', 'kategori']);
        });

        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->softDeletes();
            $table->index('tanggal');
        });

        // 2. Create activity_logs table for audit trail
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('activity');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');

        Schema::table('pengeluarans', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
            $table->dropColumn('deleted_at');
        });

        Schema::table('pemasukans', function (Blueprint $table) {
            $table->dropIndex(['tanggal', 'kategori']);
            $table->dropColumn('deleted_at');
        });

        Schema::table('penghunis', function (Blueprint $table) {
            $table->dropIndex(['tanggal_keluar']);
            $table->dropColumn('deleted_at');
        });

        Schema::table('kamars', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};
