<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('requires_approval')->default(false)->after('payment_status');
            $table->boolean('admin_approved')->default(false)->after('requires_approval');
            $table->timestamp('approved_at')->nullable()->after('admin_approved');
            $table->string('tc_kimlik')->nullable()->after('approved_at');
            $table->date('dogum_tarihi')->nullable()->after('tc_kimlik');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'requires_approval',
                'admin_approved',
                'approved_at',
                'tc_kimlik',
                'dogum_tarihi',
            ]);
        });
    }
};
