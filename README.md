# Getting started

1. Download composer.phar and install modules
```
$ php composer.phar install
```

2. Install npm dependencies
```
$ npm i
```

3. Copy /app/config/parameters.yml.dist to /app/config/parameters.yml and set up database connection params. 


4. Run gulp in a project folder for making build
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