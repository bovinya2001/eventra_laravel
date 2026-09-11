<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        if (Schema::hasColumn('users', 'is_admin')) {
            $admins = DB::table('users')->where('is_admin', true)->get();

            foreach ($admins as $admin) {
                DB::table('admins')->insert([
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'email_verified_at' => $admin->email_verified_at,
                    'password' => $admin->password,
                    'remember_token' => $admin->remember_token,
                    'created_at' => $admin->created_at,
                    'updated_at' => $admin->updated_at,
                ]);
            }

            DB::table('users')->where('is_admin', true)->delete();
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('email');
            });
        }

        Schema::dropIfExists('admins');
    }
};
