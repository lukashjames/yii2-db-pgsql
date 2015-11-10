<?php

/**
 * Author: lukashjames@gmail.com 
 */
namespace lukashjames\yii2-db-pgsql\db\pgsql;
//namespace pgsql_ext\db\pgsql;

use yii\base\InvalidParamException;

/**
 * This class extends \yii\db\pgsql\QueryBuilder class
 * for PostgreSQL.
 * Added some specific commands like 
 * CREATE SCHEMA/DROP SCHEMA, GRANT/REVOKE ...
 */
class QueryBuilder extends \yii\db\pgsql\QueryBuilder
{
    /**
     * Supported types for GRANT/REVOKE 
     * 
     * @var mixed
     * @access private
     */
    private $_grant_types = [
        'table' => ['sql' => 'TABLE'],
        'schema' => ['sql' => 'SCHEMA'],
    ];

    /**
     * Constructor 
     * 
     * @param mixed $connection 
     * @param array $config 
     * @access public
     * @return void
     */
    public function __construct($connection = null, $config = [])
    {
        if ($connection === null && isset($config['db'])) {
            $connection = $config['db'];
        }
        $this->db = $connection;
        parent::__construct($this->db, $config);
    }
    
    /**
     * Creates a SQL command for creating DB schema 
     * 
     * @param  string $schema the name of the schema to be created
     * @param  string $owner  owner for new schema
     * @access public
     * @return string the SQL statement for creating DB schema
     */
    public function createSchema($name, $owner = null)
    {
        $sql = 'CREATE SCHEMA ' . $this->db->quoteTableName($name);
        if (!empty($owner)) {
            $sql .= ' AUTHORIZATION ' . $owner;
        }
        return $sql;
    }

    /**
     * Creates a SQL command for dropping DB schema 
     * 
     * @param  string  $schema  the name of the schema to be dropped
     * @param  boolean $cascade use CASCADE statement in SQL query or not (default - false)
     * @access public
     * @return string the SQL statement for dropping DB schema
     */
    public function dropSchema($name, $cascade = false)
    {
        $sql = 'DROP SCHEMA ' . $this->db->quoteTableName($name);
        if ($cascade === true) {
            $sql .= ' CASCADE';
        }
        return $sql;
    }

    /**
     * Check if type supported 
     * 
     * @param string $type 
     * @access private
     * @return boolean
     */
    private function validGrantType($type)
    {
        return array_key_exists($type, $this->_grant_types);
    }

    /**
     * Old method 
     */
    /*public function grantOnSchema($name, $role)
    {
        $sql = 'GRANT ALL PRIVILEGES ON SCHEMA ' . $this->db->quoteTableName($name)
             . ' TO GROUP ' . $role;
        return $sql;
    }*/
    
    /**
     * Old method 
     */
    /*public function revokeOnSchema($name, $role)
    {
        $sql = 'REVOKE ALL PRIVILEGES ON SCHEMA ' . $this->db->quoteTableName($name)
             . ' FROM GROUP ' . $role;
        return $sql;
    }*/

    /**
     * Creates a SQL command for dropping DB schema 
     * 
     * @param  string  $schema  the name of the schema to be dropped
     * @param  boolean $cascade use CASCADE statement in SQL query or not (default - false)
     * @access public
     * @return string the SQL statement for dropping DB schema
     */
    public function grant($target_name, $role, $target_type = 'table')
    {
        $target_type = strtolower($target_type);
        if (false === $this->validGrantType($target_type)) {
            throw new InvalidParamException('Unsupported GRANT type');
        }
        $sql = 'GRANT ALL PRIVILEGES ON ' . $this->_grant_types[$target_type]['sql']
             . ' ' . $this->db->quoteTableName($target_name)
             . ' TO GROUP ' . $role;
        return $sql;
    }

    /**
     * Creates a SQL command for revoking on DB object from DB role 
     * 
     * @param string $target_name name of the target (table, schema, sequence, etc)
     * @param string $role existing role in DB
     * @param string $target_type type of the target (table, schema, sequence, etc)
     * @access public
     * @return string the SQL statement for dropping DB schema
     */
    public function revoke($target_name, $role, $target_type = 'table')
    {
        $target_type = strtolower($target_type);
        if (false === $this->validGrantType($target_type)) {
            throw new InvalidParamException('Unsupported GRANT type');
        }
        $sql = 'REVOKE ALL PRIVILEGES ON ' . $this->_grant_types[$target_type]['sql']
             . ' ' . $this->db->quoteTableName($target_name)
             . ' FROM GROUP ' . $role;
        return $sql;
    }
}