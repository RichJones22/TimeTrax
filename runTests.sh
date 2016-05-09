#!/usr/bin/env bash

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