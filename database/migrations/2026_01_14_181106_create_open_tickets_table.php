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
        Schema::create('open_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('ticket_number')->unique(); // index via unique
            $table->integer('is_parent')->default(0);
            $table->integer('platform_id')->default(1);
            $table->integer('user_id');
            $table->integer('status_updated_by');
            $table->integer('assigned_agent_id')->nullable();

            $table->unsignedInteger('business_entity_id');
            $table->unsignedInteger('client_id_helpdesk');
            $table->string('client_id_vendor')->nullable();

            $table->integer('source_id')->nullable();
            $table->unsignedInteger('cat_id')->nullable();
            $table->unsignedInteger('subcat_id')->nullable();

            $table->unsignedInteger('status_id');
            $table->unsignedInteger('team_id');

            $table->string('priority_name');
            $table->text('note')->nullable();
            $table->string('mobile_no')->nullable();

            $table->timestamps();

            // Optional indexes (recommended)
            $table->index('user_id');
            $table->index('status_id');
            $table->index('team_id');
            $table->index('business_entity_id');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_tickets');
    }
};
