<?php

/**
 * Author: lukashjames@gmail.com 
 */
namespace lukashjames\pgsql\db\pgsql;

/**
 * This class extends \yii\db\pgsql\Schema class
 * for PostgreSQL.
 */
class Schema extends \yii\db\pgsql\Schema
{
    private $_builder;

    /**
     * Creates new query builder for current connection 
     * 
     * @access public
     * @return object new query builder
     */
    public function createQueryBuilder()
    {
        return new QueryBuilder($this->db);
    }
    
    /**
     * Returns query builder for current connection 
     * 
     * @access public
     * @return object new query builder
     */
    public function getQueryBuilder()
    {
        //die('Schema.getQueryBuilder');
        if ($this->_builder === null) {
            $this->_builder = $this->createQueryBuilder();
        }
        return $this->_builder;
    }
    
}
