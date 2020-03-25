<?php

use Medoo\Medoo;

class DB
{
    public static function connect()
    {
        $config = config('database');

        return new Medoo([
            'database_type' => 'mysql',
            'server'        => $config['host'],
            'port'          => $config['port'],
            'database_name' => $config['database'],
            'username'      => $config['username'],
            'password'      => $config['password']
        ]);
    }

    /**
     * Select data
     *
     * @param string $table
     * @param array $what
     * @param array $where
     * 
     * @return array|null
     */
    public static function select($table, $what, $where)
    {
        $db = self::connect();

        return $db->select($table, $what, $where);
    }
}
