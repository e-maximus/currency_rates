# Getting started

 - Download composer.phar and install modules
```
$ php composer.phar install
```

 - Install npm dependencies
```
$ npm i
```

 - Copy /app/config/parameters.yml.dist to /app/config/parameters.yml and set up database connection params. 


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