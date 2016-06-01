Recovering an InnoDB table from only an *.ibd file for Yii 2
============================================================
[![Latest Stable Version](https://poser.pugx.org/efureev/yii2-recover-innodb-table/v/stable)](https://packagist.org/packages/efureev/yii2-recover-innodb-table) 
[![Total Downloads](https://poser.pugx.org/efureev/yii2-recover-innodb-table/downloads)](https://packagist.org/packages/efureev/yii2-recover-innodb-table) 
[![Latest Unstable Version](https://poser.pugx.org/efureev/yii2-recover-innodb-table/v/unstable)](https://packagist.org/packages/efureev/yii2-recover-innodb-table) 
[![License](https://poser.pugx.org/efureev/yii2-recover-innodb-table/license)](https://packagist.org/packages/efureev/yii2-recover-innodb-table)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --dev --prefer-dist efureev/yii2-recover-innodb-table:"~0.1.0"
```

or add

```json
"efureev/yii2-recover-innodb-table": "~0.1.0"
```

to the `require-dev` section of your `composer.json`.


Usage
-----

To use this extension, simply add the following code in your application configuration (console.php):

```php
'controllerMap' => [
    'utilsdb' => [
        'class' => 'efureev\utilsdb\recoverinnodb\RecoverController',
    ],
],
```

Recovering (Exp., database name `dbase`):
------------------------------------------
1. Backup original db (Exp.: `/usr/var/mysql/dbase/`) to other dir (Exp.: `/usr/var/mysql/dbase_bak`)
2. Delete original db
3. Create new db with the same name (Exp.: `/usr/var/mysql/dbase/`)
4. Copy all files (`*.ibd` and `*.frm`) from backup dir to new created dir
5. Run script: `./yii utilsdb/repair-table /usr/var/mysql/dbase /usr/var/mysql/dbase_bak`

```
# to start recover db
php yii utilsdb/repair-table <original> <backup>
```