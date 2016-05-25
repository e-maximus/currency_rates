# Getting started

1. Download composer.phar and install modules
```
$ php composer.phar install
```

2. Install npm dependencies
```
$ npm i
```

3. Run gulp in a project folder for making build
```
$ gulp
```

# Usage

Update currency rates
```
$ php app/console currencies:update
```

Change currency.active_data_provider if you like to change data provider
```
parameters:
    locale: en
    currency.active_data_provider: currency_data_provider_rsb

```