<?php

use Medoo\Medoo;

define('DB_USERNAME', 'ramon');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'netkicks');
define('DB_PATH', 'localhost');
define('DB_TYPE', 'mysql');

//class Database {
//  public static $db = NULL;
//  public function __construct() {
//    if(Database::$db == NULL) {
//      Database::$db  = new Medoo([
//        'database_type' =>  DB_TYPE,
//        'database_name' => DB_NAME,
//        'server' => DB_PATH,
//        'username' => DB_USERNAME,
//        'password' => DB_PASSWORD,
//      ]);
//    }
//  }
//}

//$db = new Database();
//$db = Database::$db;

$db  = new Medoo([
  'database_type' =>  DB_TYPE,
  'database_name' => DB_NAME,
  'server' => DB_PATH,
  'username' => DB_USERNAME,
  'password' => DB_PASSWORD,
]);

require_once('Utils.php');