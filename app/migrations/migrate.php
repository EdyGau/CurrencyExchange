<?php

$migrationFile = '20230618100000_create_currency_rates_table.php';

if (file_exists($migrationFile)) {
    require_once $migrationFile;
} else {
    echo "Migration file not found: $migrationFile";
}