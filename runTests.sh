#!/usr/bin/env bash


echo ""
echo " Timecard View Tests"
echo ""
echo ""
echo "=============================================================================="
echo "running ./tests/SeleniumTest/TimeCardView/testTimeCardViewClientSide.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TimeCardView/testTimeCardViewClientSide.php

echo "=============================================================================="
echo "running ./tests/SeleniumTest/TimeCardView/testTimeCardViewServerSide.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TimeCardView/testTimeCardViewServerSide.php

echo "=============================================================================="
echo "running ./tests/SeleniumTest/TimeCardView/testTimeCardViewRDBMS.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TimeCardView/testTimeCardViewRDBMS.php

echo ""
echo " Task View Tests"
echo ""
echo ""
echo "=============================================================================="
echo "running ./tests/SeleniumTest/TaskView/testTaskViewClientSide.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TaskView/testTaskViewClientSide.php

echo "=============================================================================="
echo "running ./tests/SeleniumTest/TaskView/testTaskViewServerSide.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TaskView/testTaskViewServerSide.php

echo "=============================================================================="
echo "running ./tests/SeleniumTest/TaskView/testTaskViewRDBMS.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TaskView/testTaskViewRDBMS.php

echo "=============================================================================="
echo "running ./tests/SeleniumTest/TaskView/testTaskViewClientSideInteractingWithTaskTypeView.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TaskView/testTaskViewClientSideInteractingWithTaskTypeView.php

echo ""
echo " Task Type View Tests"
echo ""
echo ""
echo "=============================================================================="
echo "running ./tests/SeleniumTest/TaskTypeView/testTaskTypeViewClientSide.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TaskTypeView/testTaskTypeViewClientSide.php

echo "=============================================================================="
echo "running ./tests/SeleniumTest/TaskTypeView/testTaskTypeServerSide.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TaskTypeView/testTaskTypeServerSide.php

echo "=============================================================================="
echo "running ./tests/SeleniumTest/TaskTypeView/testTaskTypeRDBMS.php"
echo "=============================================================================="
phpunit ./tests/SeleniumTest/TaskTypeView/testTaskTypeRDBMS.php
