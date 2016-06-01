<?php

namespace efureev\utilsdb\recoverinnodb;

use yii\console\Controller;

/**
 * Class RecoverController
 * @package efureev\utilsdb\recoverinnodb
 */
class RecoverController extends Controller
{
    public
        $excludeTables = [];

    public function actionRepairTable($pathDb, $pathDbBak, $mode = 0660)
    {

        DbUtils::repairTable($pathDb, $pathDbBak, $mode, $this->excludeTables);
//        $tools->repairTable('/usr/local/var/mysql/miladoma_auth', '/usr/local/var/mysql/miladoma_auth_bak', $excludeTables);
    }
}
