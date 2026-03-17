<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->bigInteger('uang_diterima')->nullable()->after('total');
            $table->bigInteger('kembalian')->nullable()->after('uang_diterima');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn(['uang_diterima', 'kembalian']);
        });
    }
};
