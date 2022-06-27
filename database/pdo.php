<?php
$engine = "mysql";
$host = "localhost";
$port = 3306; // can be modify. gate for mysql
$dbname = "db_fakebook_ajltvv";
$username = "root";
$password = "root";
$pdo = new PDO("$engine:host=$host:$port;dbname=$dbname", $username, $password);
