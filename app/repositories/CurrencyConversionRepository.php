<?php

namespace app\repositories;

use PDO;
use app\models\CurrencyConversionModel;

class CurrencyConversionRepository
{
    const LIMIT = 5;
    private $conn;
    private $tableName = 'currency_conversion';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * @param string $fromCurrency
     * @param string $toCurrency
     * 
     * @return array|null
     */
    public function findByConversion($amount, $fromCurrency, $toCurrency)
    {
        $sql = "SELECT * FROM $this->tableName WHERE amount = ? AND from_currency = ? AND to_currency = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(1, $amount, PDO::PARAM_STR);
        $stmt->bindValue(2, $fromCurrency, PDO::PARAM_STR);
        $stmt->bindValue(3, $toCurrency, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            return $result;
        } else {
            return null;
        }
    }

    /**
     * Find the last few currency conversions depending on the declared limit.
     */
    public function findLastCurrencyConversions()
    {
        $sql = "SELECT amount, from_currency, to_currency, converted_amount FROM $this->tableName ORDER BY conversion_date DESC LIMIT " . self::LIMIT;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $conversions = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $conversions[] = $row;
        }

        return $conversions;
    }

    /**
     * To check if a table in a database contains any data or is empty.
     * 
     * @return bool
     */
    public function hasDataInTable(): bool
    {
        $query = "SELECT COUNT(*) FROM $this->tableName";
        $stmt = $this->conn->query($query);
        $result = $stmt->fetchColumn();

        if ($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function saveCurrencyConverted(CurrencyConversionModel $conversion)
    {
        if (isset($conversion)) {
            $sql = "INSERT INTO $this->tableName (amount, from_currency, to_currency, converted_amount, conversion_date) 
            VALUES (:amount, :from_currency, :to_currency, :converted_amount, :conversion_date)";

            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':amount', $conversion->getAmount(), PDO::PARAM_STR);
            $stmt->bindValue(':from_currency', $conversion->getFromCurrency(), PDO::PARAM_STR);
            $stmt->bindValue(':to_currency', $conversion->getToCurrency(), PDO::PARAM_STR);
            $stmt->bindValue(':converted_amount', $conversion->getConvertedAmount(), PDO::PARAM_STR);
            $stmt->bindValue(':conversion_date', $conversion->getConversionDate());
            $stmt->execute();

            return $conversion->getConvertedAmount();
        } else {
            throw new \Exception('Incorrect currency data.');
        }
    }

    /**
     * Update conversion.
     *
     * @param int $id
     * @param CurrencyConversionModel $conversion
     * @throws Exception If currencies data are incorrect.
     */
    public function updateCurrencyConverted($id, CurrencyConversionModel $conversion)
    {
        if (isset($conversion)) {
            $sql = "UPDATE $this->tableName SET converted_amount = :converted_amount, conversion_date = :conversion_date WHERE id = :id";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':converted_amount', $conversion->getConvertedAmount(), PDO::PARAM_STR);
            $stmt->bindValue(':conversion_date', $conversion->getConversionDate());
            $stmt->execute();

            return $conversion->getConvertedAmount();
        } else {
            throw new \Exception('Incorrect currency data.');
        }
    }
}
