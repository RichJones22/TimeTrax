<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use \App\Helpers\appGlobals;

class TrigTaskBeforeInsert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared(sprintf("
        CREATE TRIGGER tr_insert_endTime_gt_startTime
            BEFORE INSERT
            ON task FOR EACH ROW
        BEGIN
            IF (select count(1)
                  from task
                 where NEW.time_card_id = task.time_card_id
                   and
                       (NEW.start_time =  task.start_time) or
                       (NEW.start_time >  task.start_time  and
                        NEW.start_time < task.end_time)   or
                       (NEW.end_time   >  task.start_time  and
                        NEW.end_time   <   task.end_time))
            THEN
                SIGNAL SQLSTATE '%d'
                SET MESSAGE_TEXT = 'task table can not have start_time > end_time';
            END IF;
        END
        ", appGlobals::TBL_TASK_START_TIME_GT_END_TIME));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_insert_endTime_gt_startTime`');
    }
}
