<?php

class DBConnect
{
	public static function getConnection()
	{
		$dsn = "mysql:host=localhost;dbname=currencies_db;charset=utf8mb4";
		$options = [
			PDO::ATTR_EMULATE_PREPARES   => false,
			PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		];
		try {
			$pdo = new PDO($dsn, "root", "", $options);
			return $pdo;
		} catch (Exception $e) {
			error_log($e->getMessage());
			exit('Connect error');
		}
	}
}
