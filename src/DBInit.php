<?php

namespace TestTask;

use TestTask\Helpers\DB;

class DBInit
{
    static public function init()
    {
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'testov'";
        $res = DB::query($query);
        if (count($res) == 0) {
            $sql = file_get_contents('database.sql');
            $commands = explode(';', $sql);
            foreach ($commands as $command) {
                $command = trim($command);
                if ($command !== '') {
                    DB::query($command);
                }
            }
        }
    }
}
