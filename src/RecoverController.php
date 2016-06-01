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
        $excludeTables = [],
        $mode = 0644;

    public function actionRepairTable($pathDb, $pathDbBak)
    {
        DbUtils::repairTable($pathDb, $pathDbBak, $this->excludeTables, $this->mode);
//        $tools->repairTable('/usr/local/var/mysql/miladoma_auth', '/usr/local/var/mysql/miladoma_auth_bak', $excludeTables);
    }
}
