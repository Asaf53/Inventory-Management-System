<?php
$host = '127.0.0.1';
$db = 'inventory-management-system-db';
$dsn = "mysql:host=$host;dbname=$db;";
$username = 'root';
$password = '';
$pdo = new PDO($dsn, $username, $password);
