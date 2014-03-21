Banker
======

Banker is a small symfony application which allows you to easy overview your
bank transactions.
I have written this application in a few hours because I couldn't find any
existing software that allows you to easily overview your expenses and incomes,
discover invalid transactions and gives you a forecast.

Current features:

- Import ING csv files
- Categorize transactions by adding your own filters
- View and edit transactions
- Reportview: summary per category per month

Planned features:

- Schedule recurring transactions
- Detect invalid transactions
- Reportview: planned versus actual

Installation
------------

1.  Clone this repository

    git clone git@github.com:yoshz/banker.git banker/

2.  Create app/config/parameters.yml and modify the file to your needs

    cp app/config/parameters.yml.dist app/config/parameters.yml

3.  Create app/config/banker.yml, you can modify it later

    cp app/config/banker.yml.dist app/config/banker.yml

3.  Install composer

    cd banker/
    curl -s http://getcomposer.org/installer | php

4.  Run composer install

    php composer.phar install

5.  Create database scheme

    php app/console doctrine:schema:create


Categories
----------

It is possible to categorize your transactions.
Configuration for this is done in app/config/banker.yml.

### Adding categories
You can add a new category by adding a new line under "categories".
For example:

    categories:
        - House
        - Taxes
        - Savings

### Adding filters
You can add a new filter by adding a new line under "filters".
For example:

    filters:
        - {filter: "My bankaccount", category: "House"}
        - {filter: "My savingsaccount", category: "Savings"}

The filter property will by used the search all transaction properties.
When it matches the category property will by assigned.
The filters will be matched in the order they are defined.

### Apply filters
The filters will only apply if your run the following command.
Normally done after an import.

    php app/console banker:applyfilters


Import CSV file
---------------

Currently only ING CSV files with date format yyyymmdd are supported.

1.  Login into your account on http://mijn.ing.nl/ and generate an export file

2.  Run the following command

    php app/console banker:ingimport path_to_your_file


