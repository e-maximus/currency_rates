# Getting started

 - Download composer.phar, install modules and set up project params during installation
```
$ php composer.phar install
```

 - create database, if it's not exists
```
$ php app/console doctrine:database:create
```

 - create tables
```
$ php app/console doctrine:schema:update --force
```

 - Install npm dependencies
```
$ npm i
```

 - Run gulp in a project folder for making build and watching for changes
```
$ gulp
```

# Usage

Update currency rates
```
$ php app/console currencies:update
```

Change currency.active_data_provider (/app/config/parameters.yml) if you like to change data provider
```
parameters:
    currency.active_data_provider: currency_data_provider_yahoo

```