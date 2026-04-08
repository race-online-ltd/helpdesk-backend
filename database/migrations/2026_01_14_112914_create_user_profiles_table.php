<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('user_type',20);
            $table->string('fullname',100)->nullable();
            $table->string('email_primary',50)->unique()->nullable();
            $table->string('email_secondary',50)->unique()->nullable();
            $table->string('mobile_primary',20)->unique()->nullable();
            $table->string('mobile_secondary',20)->unique()->nullable();
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('default_entity_id');
            $table->string('one_time_password',50);
            $table->integer('password_change')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
