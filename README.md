Description
-----------
This is a simple Yii2 extension for PostgreSQL database.

Features
--------

* createSchema(): creating a schema
* dropSchema(): removing a schema
* grant(): grant priviliges on database object to user
* revoke(): revoke privileges on database object from user

Installation
------------

Put these lines in your composer.json:

```javascript
"require-dev": {
    ...
    "lukashjames/yii2-db-pgsql": "*"
}
```

Then execute

```
composer update -v
```

Configuration
-------------

For using in Yii2 migration tool, you need edit config/console.php:

```php
'components' => [
    ...
    'dblocal' => [
        //'class' => 'yii\db\Connection',
        'class' => 'lukashjames\pgsql\db\Connection',
        'dsn' => 'pgsql:host=localhost;port=5432;dbname=mydb',
        'username' => 'postgres', // we need superuser for changing database structure
        'password' => '', // trust connection from localhost, see pg_hba.conf
        'charset' => 'utf8',
    ],
```

Using as migration tool
-----------------------

For creating new migration execute this console command:

```
yii migrate/create some_migration \
    --migrationPath=./migrations \
    --db=dblocal \
    --templateFile=@vendor/lukashjames/yii2-db-pgsql/views/migration.php
```

It generates this migration file

```php
<?php
use yii\db\Schema;
use lukashjames\pgsql\db\Migration;

class m151110_082802_some_migration extends Migration
{
    public function up()
    {

    }

    public function down()
    {
        echo "m151110_082802_some_migration cannot be reverted.\n";
        return false;
    }
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
```

Methods
-------

**createSchema()**

```php
public function createSchema($schema, $owner = null)
```

Method for creating new schema.

*Example 1:*

```php
$this->createSchema('new_schema');
```

Generates SQL statement

```sql
CREATE SCHEMA IF NOT EXISTS new_schema
```

*Example 2*

```php
$this->createSchema('other_schema', 'stat1');
```
    
Generates SQL statement

```sql
CREATE SCHEMA IF NOT EXISTS other_schema AUTHORIZATION stat1
```
    
Role stat1 must be exists in database.

**dropSchema()**

```php
public function dropSchema($schema, $cascade = false)
```

Method for removing schema.

*Example 1*

```php
$this->dropSchema('new_schema');
```
    
Generates SQL statement
    
```sql
DROP SCHEMA IF EXISTS new_schema
```
        
*Example 2*

```php
$this->dropSchema('new_schema', true);
```

Generates SQL statement

```sql
DROP SCHEMA IF EXISTS new_schema CASCADE
```
    
**grant()**

```php
public function grant($target_name, $role, $target_type = 'table', $privileges = 'all')
```

Give privileges on database object (schema, table, sequence, etc) to existing role.

Default target is table. Default privileges are 'all' (ALL PRIVILEGES)

*Example 1*

```php
$this->grant('some_table', 'stat1', 'table');
```
    
Generate SQL statement

```sql
GRANT ALL PRIVILEGES ON TABLE some_table TO stat1
```

*Example 2*

```php
$this->grant('some_schema', 'stat1', 'schema', 'usage');
```
    
Generates SQL statement

```sql
GRANT USAGE ON SCHEMA some_schema TO stat1
```

*Example 3*
```php
$this->grant('some_table_id_seq', 'stat1', 'sequence', 'usage,update');
```

Generates SQL statement

```sql
GRANT USAGE,UPDATE ON SEQUENCE some_table_id_seq TO stat1
```

**revoke()**

```php
public function revoke($target_name, $role, $target_type = 'table', $privileges)
```

Remove all privileges on database object (schema, table, sequence, etc) from existing role.

Default target is table. Default privileges are 'all' (ALL PRIVILEGES)

*Example 1*

```php
$this->revoke('some_table', 'stat1', 'table');
```
    
Generate SQL statement

```sql
REVOKE ALL PRIVILEGES ON TABLE some_table FROM stat1
```

*Example 2*

```php
$this->revoke('some_schema', 'stat1', 'schema', 'usage');
```
    
Generates SQL statement

```sql
REVOKE USAGE ON SCHEMA some_schema FROM stat1
```
    
*Example 3*
```php
$this->revoke('some_table_id_seq', 'stat1', 'sequence', 'usage,update');
```

Generates SQL statement

```sql
REVOKE USAGE,UPDATE ON SEQUENCE some_table_id_seq FROM stat1
```

Supported privileges
--------------------

* Tables: select, update, insert, delete
* Schemas: create, usage
* Sequences: usage, select, update
* Functions: execute
* Languages: usage
* Tablespaces: create
* Types: usage

TODO
----

* Add other database object for GRANT/REVOKE (sequence, function, language, tablespace, type).
* Add separate privileges for concrete database object instead ALL PRIVILEGES (SELECT/INSERT/UPDATE/DELETE for tables, CREATE/USAGE for schemas, etc)
* Something else
