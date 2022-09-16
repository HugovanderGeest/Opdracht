<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->after('password')->nullable();
            $table->boolean('checked')->after('address')->nullable();
            $table->text('description')->after('checked')->nullable();
            $table->string('interest')->after('description')->nullable();
            $table->date('date_of_birth')->after('interest')->nullable();
            $table->string('account')->after('date_of_birth')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
