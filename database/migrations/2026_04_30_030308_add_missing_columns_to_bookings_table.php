<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'seat_numbers')) {
                $table->string('seat_numbers')->nullable()->after('seat_number');
            }
            if (!Schema::hasColumn('bookings', 'seats_count')) {
                $table->integer('seats_count')->default(1)->after('seat_numbers');
            }
            if (!Schema::hasColumn('bookings', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0)->after('seats_count');
            }
            if (!Schema::hasColumn('bookings', 'reference')) {
                $table->string('reference')->nullable()->after('booking_reference');
            }
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['seat_numbers', 'seats_count', 'total_amount', 'reference']);
        });
    }
};