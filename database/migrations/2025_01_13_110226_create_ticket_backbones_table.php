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
        Schema::create('ticket_backbones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ticket_number');
            $table->unsignedInteger('backbone_element_id');
            $table->unsignedInteger('backbone_element_list_id');
            $table->unsignedInteger('backbone_element_list_id_a_end');
            $table->unsignedInteger('backbone_element_list_id_b_end');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_backbones');
    }
};
