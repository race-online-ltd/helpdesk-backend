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
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('template_name');
            $table->text('template');
            $table->string('status')->default('Active'); // Active / Inactive
            $table->integer('business_entity_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->integer('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->integer('event_id')->nullable()->constrained('events')->nullOnDelete();
            $table->json('exclude_notify')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_templates');
    }
};
