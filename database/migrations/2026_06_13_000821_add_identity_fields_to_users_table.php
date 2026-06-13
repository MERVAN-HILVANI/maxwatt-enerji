<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('tc_kimlik')->nullable()->after('district');
            $table->date('dogum_tarihi')->nullable()->after('tc_kimlik');
            $table->boolean('is_verified')->default(false)->after('dogum_tarihi');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tc_kimlik', 'dogum_tarihi', 'is_verified']);
        });
    }
};
