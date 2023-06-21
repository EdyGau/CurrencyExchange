# Currency Converter

Currency Converter is a simple web application that allows you to convert currencies. It is built using PHP and utilizes the Composer package manager for dependency management. The application also integrates the Twig templating engine for efficient and dynamic rendering of views.

# Installation

Clone the repository to your local environment. To install the required dependencies run: 

    composer install

# Configuration

Enter your database connection details in the src/database/db_connect.php file.

To run migrations execute command in terminal:

    cd src/migrates
    php 20230618100000_create_currency_rates_table.php

To populate the database with random currency conversions, run the fixtures/generate_currency_fixtures.php:

    cd app/fixtures
    php generate_currency_fixtures.php

This script will add few random currency conversion records to the currency_conversion table.

# Technologies Used

PHP

Composer

Twig


## Authors

- [@edygau](https://www.github.com/edygau)

