<?php
$host = '127.0.0.1';
$db = 'inventory-management-system';
$port = '3309';
$dsn = "mysql:host=$host;dbname=$db;port=$port";
$username = 'root';
$password = '';
$pdo = new PDO($dsn, $username, $password);
