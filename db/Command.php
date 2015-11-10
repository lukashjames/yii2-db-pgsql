<?php

/**
 * Author: lukashjames@gmail.com 
 */
namespace lukashjames\yii2-db-pgsql\db;
//namespace pgsql_ext\db;

/**
 * This class extends \yii\db\Command class
 * for PostgreSQL.
 * Added some specific commands like 
 * CREATE SCHEMA/DROP SCHEMA, GRANT/REVOKE ...
 */
class Command extends \yii\db\Command
{
    /**
     * Creates a SQL command for creating DB schema 
     * 
     * @param  string $schema the name of the schema to be created
     * @param  string $owner  owner for new schema
     * @access public
     * @return $this the command object itself
     */
    public function createSchema($schema, $owner = null)
    {
        $sql = $this->db->getQueryBuilder()->createSchema($schema, $owner);
        return $this->setSql($sql);
    }

    /**
     * Creates a SQL command for dropping DB schema 
     * 
     * @param  string  $schema  the name of the schema to be dropped
     * @param  boolean $cascade use CASCADE statement in SQL query or not (default - false)
     * @access public
     * @return $this the command object itself
     */
    public function dropSchema($schema, $cascade = false)
    {
        $sql = $this->db->getQueryBuilder()->dropSchema($schema, $cascade);
        return $this->setSql($sql);
    }

    /**
     * Old method
     */
    /*public function grantOnSchema($schema, $role)
    {
        $sql = $this->db->getQueryBuilder()->grantOnSchema($schema, $role);
        return $this->setSql($sql);
    }*/

    /**
     * Old method 
     */
    /*public function revokeOnSchema($schema, $role)
    {
        $sql = $this->db->getQueryBuilder()->revokeOnSchema($schema, $role);
        return $this->setSql($sql);
    }*/

    /**
     * Creates a SQL command for granting on DB object to DB role 
     * 
     * @param string $target_name name of the target (table, schema, sequence, etc)
     * @param string $role existing role in DB
     * @param string $target_type type of the target (table, schema, sequence, etc)
     * @access public
     * @return $this the command object itself
     */
    public function grant($target_name, $role, $target_type = 'table')
    {
        $sql = $this->db->getQueryBuilder()->grant($target_name, $role, $target_type);
        return $this->setSql($sql);
    }
    
    /**
     * Creates a SQL command for revoking on DB object from DB role 
     * 
     * @param string $target_name name of the target (table, schema, sequence, etc)
     * @param string $role existing role in DB
     * @param string $target_type type of the target (table, schema, sequence, etc)
     * @access public
     * @return $this the command object itself
     */
    public function revoke($target_name, $role, $target_type = 'table')
    {
        $sql = $this->db->getQueryBuilder()->revoke($target_name, $role, $target_type);
        return $this->setSql($sql);
    }
}
