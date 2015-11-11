<?php

/**
 * Author: lukashjames@gmail.com 
 */
namespace lukashjames\pgsql\db\pgsql;

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
     * Supported privileges for GRANT/REVOKE 
     * 
     * @var array
     * @access private
     */
    private $_grant_privileges = [
        'TABLE'      => ['SELECT', 'INSERT', 'UPDATE', 'DELETE'],
        'SCHEMA'     => ['CREATE', 'USAGE'],
        'SEQUENCE'   => ['USAGE', 'SELECT', 'UPDATE'],
        'FUNCTION'   => ['EXECUTE'],
        'LANGUAGE'   => ['USAGE'],
        'TABLESPACE' => ['CREATE'],
        'TYPE'       => ['USAGE'],
    ];
    
    private $_failed_privilege;

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
        $sql = 'CREATE SCHEMA IF NOT EXISTS ' . $this->db->quoteTableName($name);
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
        $sql = 'DROP SCHEMA IF EXISTS ' . $this->db->quoteTableName($name);
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
        return array_key_exists($type, $this->_grant_privileges);
    }

    private function validGrantPrivileges($type, $privileges)
    {
        $priv_arr = explode(',', $privileges);
        foreach ($priv_arr as $priv) {
            if (!in_array($priv, $this->_grant_privileges[$type], true)) {
                $this->_failed_privilege = $priv;
                return false;
            }
        }
        return true;
    }

    /**
     * Creates a SQL command for dropping DB schema 
     * 
     * @param  string  $schema  the name of the schema to be dropped
     * @param  boolean $cascade use CASCADE statement in SQL query or not (default - false)
     * @access public
     * @return string the SQL statement for dropping DB schema
     */
    public function grant($target_name, $role, $target_type = 'table', $privileges = 'all')
    {
        $target_type = strtoupper($target_type);
        $privileges  = strtoupper($privileges);
        if (false === $this->validGrantType($target_type)) {
            throw new InvalidParamException("\nUnsupported GRANT type");
        }
        if ($privileges === 'ALL') {
            $sub = 'ALL PRIVILEGES';
        } else {
            if (false === $this->validGrantPrivileges($target_type, $privileges)) {
                throw new InvalidParamException("\nPrivilege '{$this->_failed_privilege}' not supported for "
                    . strtolower($target_type));
            }
            $sub = $privileges;
        }
        $sql = 'GRANT ' . $sub . ' ON ' . $target_type
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
    public function revoke($target_name, $role, $target_type = 'table', $privileges = 'all')
    {
        $target_type = strtoupper($target_type);
        $privileges  = strtoupper($privileges);
        if (false === $this->validGrantType($target_type)) {
            throw new InvalidParamException("\nUnsupported REVOKE type");
        }
        if ($privileges === 'ALL') {
            $sub = 'ALL PRIVILEGES';
        } else {
            if (false === $this->validGrantPrivileges($target_type, $privileges)) {
                throw new InvalidParamException("\nPrivilege '{$this->_failed_privilege}' "
                    . "not supported for " . strtolower($target_type));
            }
            $sub = $privileges;
        }
        $sql = 'REVOKE ' . $sub . ' ON ' . $target_type
             . ' ' . $this->db->quoteTableName($target_name)
             . ' FROM GROUP ' . $role;
        return $sql;
    }
}
