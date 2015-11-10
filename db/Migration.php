<?php

/**
 * Author: lukashjames@gmail.com 2015
 */
namespace lukashjames\yii2-db-pgsql\db;
//namespace pgsql_ext\db;

/**
 * This class extends \yii\db\Migration class
 * for PostgreSQL.
 * Added some specific commands like 
 * CREATE SCHEMA/DROP SCHEMA, GRANT/REVOKE ...
 */
class Migration extends \yii\db\Migration
{
    /*public function init()
    {
        //die('Mig_init');
        parent::init();
    }*/
    
    /**
     * Builds and execute a SQL statement for creating a new DB schema
     * 
     * @param  string $schema the name of the schema to be created
     * @param  string $owner  owner for new schema
     * @access public
     * @return void
     */
    public function createSchema($schema, $owner = null)
    {
        //var_dump(get_class($this->db->createCommand()));die('MigCreSch');
        $text = "    > create schema $schema" 
              . (empty($owner) ? '' : " with owner $owner");
        echo $text;
        $time = microtime(true);
        //var_dump(get_class($this->db/*->createCommand()*/));die('MigCreSch');
        $this->db->createCommand()->createSchema($schema, $owner)->execute();
        echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
    }

    /**
     * Builds and execute a SQL statement for dropping an existing DB schema 
     * 
     * @param  string  $schema  the name of the schema to be dropped
     * @param  boolean $cascade use CASCADE statement in SQL query or not (default - false)
     * @access public
     * @return void
     */
    public function dropSchema($schema, $cascade = false)
    {
        //var_dump(get_class($this->db->createCommand()));die('MigCreSch');
        $text = "    > drop schema $schema" 
              . ($cascade === true ? ' cascade' : '');
        echo $text;
        $time = microtime(true);
        //var_dump(get_class($this->db/*->createCommand()*/));die('MigCreSch');
        $this->db->createCommand()->dropSchema($schema, $cascade)->execute();
        echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
    }

    /**
     * Old method 
     */
    /*public function grantOnSchema($schema, $role)
    {
        $text = "    > grant on schema $schema to $role";
        echo $text;
        $time = microtime(true);
        $this->db->createCommand()->grantOnSchema($schema, $role)->execute();
        echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
    }*/

    /**
     * Old method 
     */
    /*public function revokeOnSchema($schema, $role)
    {
        $text = "    > revoke on schema $schema from $role";
        echo $text;
        $time = microtime(true);
        $this->db->createCommand()->revokeOnSchema($schema, $role)->execute();
        echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
    }*/

    /**
     * Builds and execute a SQL statement for granting DB role to DB object 
     * 
     * @param string $target_name name of the target (table, schema, sequence, etc)
     * @param string $role existing role in DB
     * @param string $target_type type of the target (table, schema, sequence, etc)
     * @access public
     * @return void
     */
    public function grant($target_name, $role, $target_type = 'table')
    {
        //var_dump(get_class($this->db->createCommand()));die('MigCreSch');
        $text = "    > grant on $target_type $target_name to $role";
        echo $text;
        $time = microtime(true);
        //var_dump(get_class($this->db/*->createCommand()*/));die('MigCreSch');
        $this->db->createCommand()->grant($target_name, $role, $target_type)->execute();
        echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
    }
    
    /**
     * Builds and execute a SQL statement for revoking DB role from DB object 
     * 
     * @param string $target_name name of the target (table, schema, sequence, etc)
     * @param string $role existing role in DB
     * @param string $target_type type of the target (table, schema, sequence, etc)
     * @access public
     * @return void
     */
    public function revoke($target_name, $role, $target_type = 'table')
    {
        //var_dump(get_class($this->db->createCommand()));die('MigCreSch');
        $text = "    > revoke on $target_type $target_name from $role";
        echo $text;
        $time = microtime(true);
        //var_dump(get_class($this->db/*->createCommand()*/));die('MigCreSch');
        $this->db->createCommand()->revoke($target_name, $role, $target_type)->execute();
        echo " done (time: " . sprintf('%.3f', microtime(true) - $time) . "s)\n";
    }
}
