<?php

require_once __DIR__ . '/../database/db_connect.php';

class CreateCurrencyTables
{
    private $conn;

    public function __construct()
    {  
        $this->conn = DBConnect::getConnection();
    }

    public function up()
    {
        $queryConversion = "CREATE TABLE currency_conversion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            amount FLOAT NOT NULL,
            from_currency VARCHAR(3) NOT NULL,
            to_currency VARCHAR(3) NOT NULL,
            converted_amount FLOAT NOT NULL,
            conversion_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        $this->conn->exec($queryConversion);

        $queryRates = "CREATE TABLE currency_rates (
            id INT AUTO_INCREMENT PRIMARY KEY,
            currency VARCHAR(255) NOT NULL,
            code VARCHAR(3) NOT NULL,
            mid FLOAT NOT NULL
        )";

        $this->conn->exec($queryRates);
    }

    public function down()
    {
        $queryConversion = "DROP TABLE currency_conversion";
        $this->conn->exec($queryConversion);

        $queryRates = "DROP TABLE currency_rates";
        $this->conn->exec($queryRates);
    }
}

$migration = new CreateCurrencyTables();
$migration->up();
