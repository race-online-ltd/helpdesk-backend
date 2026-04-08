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
        // Schema::create('merge_tickets', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('ticket_number');
        //     $table->unsignedBigInteger('child_exists')->nullable();
        //     $table->unsignedBigInteger('parent_ticket_number')->nullable();
        //     $table->unsignedBigInteger('merged_by');
        //     $table->timestamps();
        // });
        DB::statement("
            CREATE TABLE merge_tickets (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                ticket_number BIGINT UNSIGNED NOT NULL,
                child_exists BIGINT UNSIGNED NULL,
                parent_ticket_number BIGINT UNSIGNED NULL,
                merged_by BIGINT UNSIGNED NOT NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                PRIMARY KEY (id, created_at)
            )
            PARTITION BY RANGE COLUMNS (created_at) (
                PARTITION merge_tickets_future
                    VALUES LESS THAN (MAXVALUE)
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merge_tickets');
    }
};
