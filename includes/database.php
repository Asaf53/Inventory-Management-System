<?php
$host = 'localhost';
$db = 'titan-cink';
$dsn = "mysql:host=$host;dbname=$db;";
$username = 'root';
$password = '';
$pdo = new PDO($dsn, $username, $password);
