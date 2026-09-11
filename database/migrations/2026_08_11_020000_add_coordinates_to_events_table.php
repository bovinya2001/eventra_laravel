<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('location_key')->nullable()->after('location');
            $table->decimal('latitude', 10, 7)->nullable()->after('location_key');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->index('location_key');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['location_key']);
            $table->dropColumn(['location_key', 'latitude', 'longitude']);
        });
    }
};
