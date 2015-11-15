<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TrigTaskBeforeInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE TRIGGER tr_insert_endTime_gt_startTime
        BEFORE INSERT
        ON task FOR EACH ROW
        BEGIN
            IF (NEW.start_time > NEW.end_time) THEN
                SIGNAL SQLSTATE '45001'
                SET MESSAGE_TEXT = 'task table can not have start_time > end_time';
            END IF;
        END
        ");

        DB::unprepared("
        CREATE TRIGGER tr_update_endTime_gt_startTime
        BEFORE UPDATE
        ON task FOR EACH ROW
        BEGIN
            IF (NEW.start_time > NEW.end_time) THEN
                SIGNAL SQLSTATE '45001'
                SET MESSAGE_TEXT = 'task table can not have start_time > end_time';
            END IF;
        END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_insert_endTime_gt_startTime`');
        DB::unprepared('DROP TRIGGER `tr_update_endTime_gt_startTime`');
    }
}
