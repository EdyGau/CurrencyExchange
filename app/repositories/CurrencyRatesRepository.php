<?php

namespace app\repositories;

use PDO;
use app\models\CurrencyRatesModel;

class CurrencyRatesRepository
{
    private $conn;
    private $tableName = 'currency_rates';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Get all currencies from DB.
     *
     * @return array
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM $this->tableName";
        $result = $this->conn->query($sql);
        $currencies = [];

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $currencies[] = $row;
            }
        }

        return $currencies;
    }

    /**
     * @param string $code
     * @return object|null
     */
    public function findByCode($code)
    {
        $sql = "SELECT * FROM $this->tableName WHERE code = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $code, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * @param CurrencyRatesModel $currency.
     * @throws Exception If currency data are incorrect.
     */
    public function save(CurrencyRatesModel $currency)
    {
        if (isset($currency)) {
            $sql = "INSERT INTO $this->tableName (currency, code, mid) VALUES (:currency, :code, :mid)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':currency', mb_strtoupper($currency->getCurrency(), 'UTF-8'), PDO::PARAM_STR);
            $stmt->bindValue(':code', $currency->getCode(), PDO::PARAM_STR);
            $stmt->bindValue(':mid', $currency->getMid(), PDO::PARAM_STR);
            $stmt->execute();
        } else {
            throw new \Exception('Incorrect currency data.');
        }
    }

    /**
     * @param int $id
     * @param CurrencyRatesModel $currency
     * @throws Exception If currencies data are incorrect.
     */
    public function update(CurrencyRatesModel $currency)
    {
        if (isset($currency['currency'])) {
            $sql = "UPDATE $this->tableName SET mid = :mid WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $currency->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':mid', $currency->getMid(), PDO::PARAM_STR);
            $stmt->execute();
        } else {
            throw new \Exception('Incorrect currency data.');
        }
    }
}
