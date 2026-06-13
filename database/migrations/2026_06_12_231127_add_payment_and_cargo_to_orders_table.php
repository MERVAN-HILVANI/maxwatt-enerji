<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('cargo_company')->nullable()->after('payment_method');
            $table->string('cargo_tracking_number')->nullable()->after('cargo_company');
            $table->string('payment_receipt')->nullable()->after('cargo_tracking_number'); // PDF dekont
            $table->string('payment_status')->default('pending')->after('payment_receipt'); // pending, confirmed, rejected
            $table->text('admin_note')->nullable()->after('payment_status');
            $table->timestamp('shipped_at')->nullable()->after('admin_note');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'cargo_company',
                'cargo_tracking_number',
                'payment_receipt',
                'payment_status',
                'admin_note',
                'shipped_at',
                'delivered_at',
            ]);
        });
    }
};
