<?php
/**
 * Created by PhpStorm.
 * User: Rich Jones
 * Date: 8/27/16
 * Time: 7:25 PM
 */

namespace tests\UnitTests;

use TestCase;
use App\Task;
use App\TaskType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use \Carbon\Carbon;


class TaskTypeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_checks_if_type_exists()
    {
        // Given
        $type = 'Bob';

        $taskType = new TaskType();
        $taskType->setType($type);
        $taskType->setDescription('was here');
        $taskType->created_at = Carbon::now();
        $taskType->updated_at = Carbon::now();
        $taskType->client_id = 1;

        $taskType->save();

        // When
        $result = TaskType::checkIfExists($type);

        // Then
        $this->assertEquals($type, $result->type);
    }

    /**
     * constraint test
     *
     * @test
     */
    public function POS_it_checks_if_id_of_type_record_exists() {

        // this assumes that a Task records exists.
        // see '/create_data; endpoint in routes file.
        $task = Task::where('id', '>', 0)->first();

        // populate task_type.id with task_type_id from task
        $taskType = new TaskType();
        $taskType->id = $task->task_type_id;

        $result = $taskType->checkTaskTypeDeleteAudits($taskType);

        // expected result
        $this->assertEquals($result, appGlobals()::TBL_TASK_TYPE_CONSTRAINT_VIOLATION);
    }

    /**
     * constraint test
     *
     * @test
     */
    public function NEG_it_checks_if_id_of_type_record_exists() {

        // populate task_type.id with invalid value.
        $taskType = new TaskType();
        $taskType->setId(-1);

        $result = $taskType->checkTaskTypeDeleteAudits($taskType);

        // expected result
        $this->assertEquals($result, 0);
    }

    /**
     * audit test
     *  - check checkIfTypeExists() -- return appGlobals()::TBL_TASK_TYPE_TYPE_ALREADY_EXISTS
     *
     * @test
     */
    public function POS_it_checks_create_audits_testing_checkIfTypeExists() {

        $type = 'Bob';

        $taskType = new TaskType();
        $taskType->setType($type);
        $taskType->setDescription('was here');
        $taskType->created_at = Carbon::now();
        $taskType->updated_at = Carbon::now();
        $taskType->client_id = 1;

        $taskType->save();

        $result = $taskType->checkTaskTypeCreateAudits($taskType);

        $this->assertEquals($result, appGlobals()::TBL_TASK_TYPE_TYPE_ALREADY_EXISTS);
    }

    /**
     * audit test
     *  - check checkIfTypeExists() -- return 0
     *
     * @test
     */
    public function NEG_it_checks_create_audits() {

        $type = 'Bob';

        $taskType = new TaskType();
        $taskType->setType($type);
        $taskType->setDescription('was here');
        $taskType->created_at = Carbon::now();
        $taskType->updated_at = Carbon::now();
        $taskType->client_id = 1;

        $result = $taskType->checkTaskTypeCreateAudits($taskType);

        $this->assertEquals($result, 0);
    }

    /**
     * audit test
     *  - check checkIfTypeContainsMultipleWords() -- return 0
     *
     * @test
     */
    public function POS_it_checks_create_audits_checking_checkIfTypeContainsMultipleWords() {

        $type = 'Bob';

        $taskType = new TaskType();
        $taskType->setType($type);
        $taskType->setDescription('was here');
        $taskType->created_at = Carbon::now();
        $taskType->updated_at = Carbon::now();
        $taskType->client_id = 1;

        $result = $taskType->checkTaskTypeCreateAudits($taskType);

        $this->assertEquals($result, 0);
    }

    /**
     * audit test
     *  - check checkIfTypeContainsMultipleWords() -- return appGlobals()::TBL_TASK_TYPE_TYPE_RESTRICTED_TO_ONE_WORD
     *
     * @test
     */
    public function NEG_it_checks_create_audits_checking_checkIfTypeContainsMultipleWords() {

        $type = 'Bob is not here';

        $taskType = new TaskType();
        $taskType->setType($type);
        $taskType->setDescription('was here');
        $taskType->created_at = Carbon::now();
        $taskType->updated_at = Carbon::now();
        $taskType->client_id = 1;

        $result = $taskType->checkTaskTypeCreateAudits($taskType);

        $this->assertEquals($result, appGlobals()::TBL_TASK_TYPE_TYPE_RESTRICTED_TO_ONE_WORD);
    }

    /**
     * updateRec() tests
     *  - check change record does not exist.
     *
     * @test
     */
    public function POS_it_checks_updateRec_change_rec_does_not_exist() {

        // create the record to check
        $type = 'Bob';
        $taskType = new TaskType();
        $taskType->setType($type);
        $taskType->setDescription('was here');
        $taskType->created_at = Carbon::now();
        $taskType->updated_at = Carbon::now();
        $taskType->client_id = 1;
        $taskType->save();

        // create the changed record
        $type = 'Bob';
        $changeTaskType = new TaskType();
        $changeTaskType->setType($type);
        $changeTaskType->setDescription('was here');
        $changeTaskType->created_at = Carbon::now();
        $changeTaskType->updated_at = Carbon::now();
        $changeTaskType->client_id = 1;
        $changeTaskType->save();

        $stdClass = new \stdClass();
        $stdClass->id = -1;
        $stdClass->type = $taskType->getType();
        $stdClass->desc = $taskType->getDescription();
        $stdClass->client_id = $taskType->client_id;

        $result = $taskType->updateRec($stdClass);

        $this->assertEquals($result, 0);
    }

    /**
     * updateRec() tests
     *  - check change record does not exist.
     *
     * @test
     */
    public function POS_it_checks_updateRec_no_change_in_att() {
        // create the record to check
        $type = 'Bob';
        $taskType = new TaskType();
        $taskType->setType($type);
        $taskType->setDescription('was here');
        $taskType->created_at = Carbon::now();
        $taskType->updated_at = Carbon::now();
        $taskType->client_id = 1;
        $taskType->save();

        // create the changed record
        $changeType = 'Bob';

        $stdClass = new \stdClass();
        $stdClass->id = $taskType->id;
        $stdClass->type = $changeType;
        $stdClass->desc = $taskType->getDescription();
        $stdClass->client_id = $taskType->client_id;

        $taskType->updateRec($stdClass);
        $result = $taskType::where('id', $taskType->getId())->first();

        $this->assertEquals($result->getType(), $changeType);
    }

    /**
     * updateRec() tests
     *  - attribute type changed.
     *
     * @test
     */
    public function POS_it_checks_updateRec_att_type_changed() {
        // create the record to check
        $type = 'Bob';
        $taskType = new TaskType();
        $taskType->setType($type);
        $taskType->setDescription('was here');
        $taskType->created_at = Carbon::now();
        $taskType->updated_at = Carbon::now();
        $taskType->client_id = 1;
        $taskType->save();

        // create the changed record
        $changeType = 'Steve';

        $stdClass = new \stdClass();
        $stdClass->id = $taskType->id;
        $stdClass->type = $changeType;
        $stdClass->desc = $taskType->getDescription();
        $stdClass->client_id = $taskType->client_id;

        $taskType->updateRec($stdClass);
        $result = $taskType::where('id', $taskType->getId())->first();

        $this->assertEquals($result->getType(), $changeType);
    }

    /**
     * updateRec() tests
     *  - attribute description changed.
     *
     * @test
     */
    public function POS_it_checks_updateRec_att_description_changed() {
        // create the record to check
        $description = 'was here';
        $taskType = new TaskType();
        $taskType->setType('Bob');
        $taskType->setDescription($description);
        $taskType->created_at = Carbon::now();
        $taskType->updated_at = Carbon::now();
        $taskType->client_id = 1;
        $taskType->save();

        // create the changed record
        $changeDescription = 'sorry bob is no longer here';

        $stdClass = new \stdClass();
        $stdClass->id = $taskType->id;
        $stdClass->type = $taskType->getType();
        $stdClass->desc = $changeDescription;
        $stdClass->client_id = $taskType->client_id;

        $taskType->updateRec($stdClass);
        $result = $taskType::where('id', $taskType->getId())->first();

        $this->assertEquals($result->getDescription(), $changeDescription);
    }


}