<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'];
$database = $_ENV['DB_DATABASE'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];

$command = "mysql --host={$host} --user={$username} --password={$password} {$database} < mysql-schema.sql";
system($command);

echo "Database imported successfully!\n";
