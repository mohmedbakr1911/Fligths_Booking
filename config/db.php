<?php
require_once __DIR__.'/config.php';
function db(): PDO {
static $pdo;
if ($pdo instanceof PDO) return $pdo;
$dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4';
$options = [
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
return $pdo;
}

?>


