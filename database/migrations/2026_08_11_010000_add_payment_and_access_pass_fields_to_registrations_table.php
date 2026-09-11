<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
            $table->string('payment_status')->default('pending')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->decimal('payment_amount', 10, 2)->default(0)->after('payment_method');
            $table->string('payment_reference')->nullable()->after('payment_amount');
            $table->timestamp('paid_at')->nullable()->after('payment_reference');
            $table->timestamp('checked_in_at')->nullable()->after('paid_at');
        });

        DB::table('registrations')->orderBy('id')->each(function (object $registration): void {
            $event = DB::table('events')->find($registration->event_id);

            DB::table('registrations')->where('id', $registration->id)->update([
                'uuid' => (string) Str::uuid(),
                'payment_status' => $registration->status === 'confirmed' ? 'paid' : 'pending',
                'payment_method' => $registration->status === 'confirmed' ? 'legacy' : null,
                'payment_amount' => $event?->price ?? 0,
                'payment_reference' => $registration->status === 'confirmed' ? 'LEGACY-'.$registration->id : null,
                'paid_at' => $registration->status === 'confirmed' ? $registration->created_at : null,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn([
                'uuid',
                'payment_status',
                'payment_method',
                'payment_amount',
                'payment_reference',
                'paid_at',
                'checked_in_at',
            ]);
        });
    }
};
