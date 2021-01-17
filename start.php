<?php
// crea el esquema

require __DIR__ . '/vendor/autoload.php';

use App\App;

$conf = App::init();

define('DSN', $conf['driver'] . ':host=' . $conf['dbhost'] . ';dbname=' . $conf['dbname']);
define('USR', $conf['dbuser']);
define('PWD', $conf['dbpass']);

$db = new \PDO(DSN, USR, PWD);

$sql = file_get_contents('database.sql');
try {
    $db->exec($sql);
} catch (PDOException $e) {
    die($e->getMessage());
}
