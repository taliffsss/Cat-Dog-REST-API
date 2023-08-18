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
        Schema::create('todo_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->unique();
            $table->softDeletesTz();
            $table->timestampTz('updated_at')->nullable()->useCurrentOnUpdate();
            $table->timestampTz('created_at')->useCurrent();
            $table->index('name');
        });

        DB::table('todo_statuses')->insert(
            [
                [
                    'name' => 'todo',
                ],
                [
                    'name' => 'blocked',
                ],
                [
                    'name' => 'in progress',
                ],
                [
                    'name' => 'done',
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('todo_statuses');
    }
};
