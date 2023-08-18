<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name')->nullable()->unique();
            $table->softDeletesTz();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestampTz('created_at')->useCurrent();
            $table->index('role_name');
        });

        DB::table('roles')->insert(
            [
                [
                    'role_name' => 'super admin',
                ],
                [
                    'role_name' => 'owner',
                ],
                [
                    'role_name' => 'manager',
                ],
                [
                    'role_name' => 'client',
                ],
                [
                    'role_name' => 'cashier',
                ],
                [  
                    'role_name' => 'staff',
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
