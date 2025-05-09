<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hospital_users', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('password');
            $table->enum('sex', ['male', 'female'])->nullable()->after('dob');
        });
    }

    public function down(): void
    {
        Schema::table('hospital_users', function (Blueprint $table) {
            $table->dropColumn(['dob', 'sex']);
        });
    }
};
