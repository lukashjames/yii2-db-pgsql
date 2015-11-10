<?php

/**
 * Author: lukashjames@gmail.com 
 */
namespace lukashjames\db-pgsql\db;

use Yii;
use lukashjames\db-pgsql\db\Command;

/**
 * This class extends \yii\db\Connection class
 * for PostgreSQL.
 * Added some specific commands like 
 * CREATE SCHEMA/DROP SCHEMA, GRANT/REVOKE ...
 */
class Connection extends \yii\db\Connection
{
    /**
     * Constructor 
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        // replace schemaMap for pgsql
        $this->schemaMap['pgsql'] = 'app\db\pgsql\Schema';
    }
    
    /**
     * Returns the query builder for the current DB connection 
     * 
     * @access public
     * @return Query builder
     */
    public function getQueryBuilder()
    {
        return $this->getSchema()->getQueryBuilder();
    }
    
    /**
     * Returns a command for execution 
     * 
     * @param string $sql the SQL statement to be executed
     * @param array $params the parameters to be bound to the SQL statement
     * @access public
     * @return Command the DB command
     */
    public function createCommand($sql = null, $params = [])
    {
        $command = new Command([
            'db' => $this,
            'sql' => $sql,
        ]);
        return $command->bindValues($params);
    }
}
