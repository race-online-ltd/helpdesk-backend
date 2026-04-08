<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
   
    public function up(): void
    {
       

        DB::statement("
            CREATE TABLE srv_time_subcat_sla_histories (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                ticket_number VARCHAR(255) NOT NULL,
                sla_subcat_config_id BIGINT UNSIGNED NOT NULL,
                sla_status TINYINT NOT NULL COMMENT '0=failed,1=success,2=started',

                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ON UPDATE CURRENT_TIMESTAMP,

                PRIMARY KEY (id, created_at),
                INDEX idx_ticket_number (ticket_number),
                INDEX idx_sla_subcat_config_id (sla_subcat_config_id),
                INDEX idx_sla_status (sla_status),
                INDEX idx_created_at (created_at),
                INDEX idx_updated_at (updated_at)
            )
            ENGINE=InnoDB
            PARTITION BY RANGE COLUMNS (created_at) (
                PARTITION srv_time_subcat_sla_histories_future
                VALUES LESS THAN (MAXVALUE)
            )
        ");
    }

    public function down(): void
    {
         DB::statement("DROP TABLE IF EXISTS srv_time_subcat_sla_histories");
    }


};
