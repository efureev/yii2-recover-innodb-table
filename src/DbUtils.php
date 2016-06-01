<?php

namespace efureev\utilsdb\recoverinnodb;

use yii\base\Component;
use yii\base\Exception;
use yii\console\Application as ConsoleApplication;
use yii\helpers\Console;
use yii\helpers\FileHelper;

/**
 * Class DbUtils
 * @package efureev\utilsdb\recoverinnodb
 */
class DbUtils extends Component
{

    public static function repairTable(
        $pathDb,
        $pathDbBak,
        $excludeTables = [],
        $mode = 0644
    )
    {
        $files = FileHelper::findFiles($pathDb, ['only' => ['*.ibd']]);
        $tables = self::getTableNames($files);
        $tables = self::filterTables($tables, $excludeTables);

        \Yii::$app->db->createCommand('set foreign_key_checks=0;')->query();

        self::msg('%b[info]%n Total tables: ' . $totalTables = count($tables));

        $i = 0;

        foreach ($tables as $table => $tableFile) {
            self::msg('%b' . ++$i . '[' . $totalTables . '] %y' . strtoupper($table) . '%n');

            try {
                \Yii::$app->db->createCommand('ALTER TABLE ' . $table . ' DISCARD TABLESPACE;')->query();
                self::msg("\tdiscard tablespace");

                $tablePathBak = $pathDbBak . '/' . $table . '.ibd';

                copy($tablePathBak, $tableFile);
                self::msg("\tcopy IBD file");

                chmod($tableFile, $mode);
                self::msg("\tchmod = " . base_convert($mode, 10, 8));

                \Yii::$app->db->createCommand('ALTER TABLE ' . $table . ' IMPORT TABLESPACE;')->query();
                self::msg("\timport tablespace");

                $tableInDb = \Yii::$app->db->createCommand('SHOW CREATE TABLE ' . $table . ';')->queryScalar();

                self::msg("\ttest: " . ($tableInDb === $table ? '%GSUCCESS' : '%RFAIL') . '%n');
            } catch (Exception $e) {
                throw $e;
            }
        }
        \Yii::$app->db->createCommand('set foreign_key_checks=1;')->query();

        self::msg('%b[info]%n Complete!');

    }

    /**
     * @param array $paths
     * @return array
     */
    protected static function getTableNames(array $paths): array
    {
        $list = [];
        foreach ($paths as $path) {
            $list[pathinfo($path, PATHINFO_FILENAME)] = $path;
        }
        return $list;
    }

    /**
     * @param array $tables
     * @param array $excludeTables
     * @return array
     */
    protected static function filterTables(array $tables, array $excludeTables): array
    {
        return array_filter($tables, function ($table) use ($excludeTables) {
            return !in_array($table, $excludeTables);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected static function msg($msg)
    {
        if (\Yii::$app instanceof ConsoleApplication) {
            Console::output(Console::renderColoredString($msg));
        }
    }


}
